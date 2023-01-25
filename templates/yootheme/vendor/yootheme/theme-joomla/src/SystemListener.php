<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Router\Route;
use Joomla\CMS\User\User;
use YOOtheme\Config;
use YOOtheme\Http\Response;

class SystemListener
{
    public static function checkPermission(
        Config $config,
        User $user,
        Response $response,
        $request,
        callable $next
    ) {
        // check user permissions
        if (
            !$request->getAttribute('allowed') &&
            !(
                $user->authorise('core.edit', 'com_content') ||
                $user->authorise('core.edit.own', 'com_content') ||
                $user->authorise('core.edit', 'com_templates') ||
                $user->authorise('core.edit', 'com_modules')
            )
        ) {
            // redirect guest user to user login
            if ($user->guest && str_contains($request->getHeaderLine('Accept'), 'text/html')) {
                return $response->withRedirect(
                    Route::_(
                        $config('app.isAdmin')
                            ? 'index.php?option=com_login'
                            : 'index.php?option=com_users&view=login',
                        false
                    )
                );
            }

            $request->abort(403, 'Insufficient User Rights.');
        }

        return $next($request);
    }
}
