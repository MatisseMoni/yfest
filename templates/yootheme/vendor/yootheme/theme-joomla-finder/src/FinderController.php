<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\File as JFile;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\User\User;
use Joomla\Input\Input;
use YOOtheme\Config;
use YOOtheme\File;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Path;

class FinderController
{
    public static function index(Request $request, Response $response, Config $config, Input $input)
    {
        // get media root and current path
        $root = $config('app.uploadDir');
        $path = Path::join($root, $input->getString('folder', ''));

        if (!str_starts_with($path, $root)) {
            $path = $root;
        }

        $files = [];

        foreach (File::listDir($path, true) ?: [] as $file) {
            $filename = basename($file);

            // ignore hidden files
            if (str_starts_with($filename, '.')) {
                continue;
            }

            $files[] = [
                'name' => $filename,
                'path' => Path::relative($root, $file),
                'url' => Path::relative(JPATH_ROOT, $file),
                'type' => File::isDir($file) ? 'folder' : 'file',
                'size' => HTMLHelper::_('number.bytes', File::getSize($file)),
            ];
        }

        return $response->withJson($files);
    }

    public static function rename(Request $request, Response $response, Config $config, User $user)
    {
        if (
            !$user->authorise('core.create', 'com_media') ||
            !$user->authorise('core.delete', 'com_media')
        ) {
            $request->abort(403, 'Insufficient User Rights.');
        }

        $allowed = ComponentHelper::getParams('com_media')->get('upload_extensions') . ',svg';
        $newName = $request->getParam('newName');
        $extension = File::getExtension($newName);
        $isValidFilename =
            !empty($newName) &&
            (empty($extension) || in_array($extension, explode(',', $allowed))) &&
            (defined('PHP_WINDOWS_VERSION_MAJOR')
                ? !preg_match('#[\\/:"*?<>|]#', $newName)
                : !str_contains($newName, '/'));

        $request->abortIf(!$isValidFilename, 400, 'Invalid file name.');

        $root = $config('app.uploadDir');
        $oldFile = Path::join($root, $request->getParam('oldFile'));
        $newFile = Path::join(dirname($oldFile), $newName);

        $request->abortIf(
            !str_starts_with($oldFile, $root) || dirname($oldFile) !== dirname($newFile),
            400,
            'Invalid path.'
        );
        $request->abortIf(!JFile::move($oldFile, $newFile), 500, 'Error writing file.');

        return $response->withJson('Successfully renamed.');
    }
}
