<?php // $file = C:/wamp64/www/yfest/templates/yootheme/vendor/yootheme/theme/config/customizer.json

return [
  '@import' => [$filter->apply('path', '../../builder/config/customizer.json', $file), $filter->apply('path', '../../styler/config/customizer.json', $file), $filter->apply('path', '../../theme-cookie/config/customizer.json', $file), $filter->apply('path', '../../theme-settings/config/customizer.json', $file)], 
  'base' => $config->get('theme.url'), 
  'name' => $config->get('theme.name'), 
  'version' => $config->get('theme.version'), 
  'help' => 'https://yootheme.com', 
  'api' => 'https://yootheme.com/api', 
  'sections' => [
    'layout' => [
      'title' => 'Layout', 
      'priority' => 10, 
      'fields' => [
        'layout' => [
          'type' => 'menu', 
          'items' => [
            'site' => 'Site', 
            'header' => 'Header', 
            'mobile' => 'Mobile', 
            'top' => 'Top', 
            'sidebar' => 'Sidebar', 
            'bottom' => 'Bottom', 
            'footer-builder' => 'Footer', 
            'system-blog' => 'Blog', 
            'system-post' => 'Post'
          ]
        ]
      ]
    ]
  ], 
  'panels' => [
    'site' => [
      'title' => 'Site', 
      'width' => 400, 
      'fields' => [
        'logo.text' => [
          'label' => 'Logo Text', 
          'description' => 'The logo text will be used, if no logo image has been picked. If an image has been picked, it will be used as an aria-label attribute on the link.'
        ], 
        'logo.image' => [
          'label' => 'Logo Image', 
          'type' => 'image'
        ], 
        'logo.image_svg_inline' => [
          'description' => 'Select your logo. Optionally inject an SVG logo into the markup so it adopts the text color automatically.', 
          'type' => 'checkbox', 
          'text' => 'Inline SVG logo'
        ], 
        'logo._image_dimension' => [
          'type' => 'grid', 
          'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
          'width' => '1-2', 
          'fields' => [
            'logo.image_width' => [
              'label' => 'Width', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ], 
            'logo.image_height' => [
              'label' => 'Height', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ]
          ], 
          'show' => 'logo.image || logo.image_inverse'
        ], 
        'logo.image_inverse' => [
          'label' => 'Inverse Logo (Optional)', 
          'description' => 'Select an alternative logo with inversed color, e.g. white, for better visibility on dark backgrounds. It will be displayed automatically, if needed.', 
          'type' => 'image'
        ], 
        'logo.image_mobile' => [
          'label' => 'Mobile Logo (Optional)', 
          'description' => 'Select an alternative logo, which will be used on small devices.', 
          'type' => 'image'
        ], 
        'logo._image_mobile_dimension' => [
          'type' => 'grid', 
          'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
          'width' => '1-2', 
          'fields' => [
            'logo.image_mobile_width' => [
              'label' => 'Width', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ], 
            'logo.image_mobile_height' => [
              'label' => 'Height', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ]
          ], 
          'show' => 'logo.image_mobile || logo.image_mobile_inverse'
        ], 
        'logo.image_mobile_inverse' => [
          'label' => 'Mobile Inverse Logo (Optional)', 
          'description' => 'Select an alternative logo with inversed color, e.g. white, for better visibility on dark backgrounds. It will be displayed automatically, if needed.', 
          'type' => 'image'
        ], 
        'logo.image_dialog' => [
          'label' => 'Dialog Logo (Optional)', 
          'description' => 'Select a logo, which will be shown in offcanvas and modal dialogs of certain header layouts.', 
          'type' => 'image'
        ], 
        'logo._image_dialog_dimension' => [
          'type' => 'grid', 
          'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
          'width' => '1-2', 
          'fields' => [
            'logo.image_dialog_width' => [
              'label' => 'Width', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ], 
            'logo.image_dialog_height' => [
              'label' => 'Height', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ]
          ], 
          'show' => 'logo.image_dialog'
        ], 
        'site.layout' => [
          'label' => 'Layout', 
          'type' => 'select', 
          'options' => [
            'Full Width' => 'full', 
            'Boxed' => 'boxed'
          ]
        ], 
        'site.boxed.alignment' => [
          'type' => 'checkbox', 
          'text' => 'Center', 
          'enable' => 'site.layout == \'boxed\''
        ], 
        'site.boxed.margin_top' => [
          'type' => 'checkbox', 
          'text' => 'Add top margin', 
          'enable' => 'site.layout == \'boxed\''
        ], 
        'site.boxed.margin_bottom' => [
          'type' => 'checkbox', 
          'text' => 'Add bottom margin', 
          'enable' => 'site.layout == \'boxed\''
        ], 
        'site.boxed.header_outside' => [
          'type' => 'checkbox', 
          'text' => 'Display header outside the container', 
          'enable' => 'site.layout == \'boxed\''
        ], 
        'site.boxed.media' => [
          'label' => 'Image', 
          'description' => 'Upload an optional background image that covers the page. It will be fixed while scrolling.', 
          'type' => 'image', 
          'enable' => 'site.layout == \'boxed\''
        ], 
        'site.boxed._media' => [
          'type' => 'button-panel', 
          'panel' => 'site-media', 
          'text' => 'Edit Settings', 
          'show' => 'site.layout == \'boxed\' && site.boxed.media'
        ], 
        'site.boxed.header_transparent' => [
          'label' => 'Transparent Header', 
          'description' => 'Make the header transparent and overlay the page background.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Overlay (Light)' => 'light', 
            'Overlay (Dark)' => 'dark'
          ], 
          'enable' => 'site.layout == \'boxed\' && site.boxed.header_outside'
        ], 
        'site.toolbar_width' => [
          'label' => 'Toolbar', 
          'type' => 'select', 
          'options' => [
            'Default' => 'default', 
            'Small' => 'small', 
            'Large' => 'large', 
            'X-Large' => 'xlarge', 
            'Expand' => 'expand'
          ]
        ], 
        'site.toolbar_center' => [
          'type' => 'checkbox', 
          'text' => 'Center'
        ], 
        'site.toolbar_transparent' => [
          'type' => 'checkbox', 
          'text' => 'Inherit transparency from header'
        ], 
        'site.breadcrumbs' => [
          'label' => 'Breadcrumbs', 
          'type' => 'checkbox', 
          'text' => 'Display the breadcrumb navigation'
        ], 
        'site.breadcrumbs_show_current' => [
          'text' => 'Show current page', 
          'type' => 'checkbox', 
          'enable' => 'site.breadcrumbs'
        ], 
        'site.breadcrumbs_show_home' => [
          'description' => 'Show or hide the home link as first item as well as the current page as last item in the breadcrumb navigation.', 
          'text' => 'Show home link', 
          'type' => 'checkbox', 
          'enable' => 'site.breadcrumbs'
        ], 
        'site.breadcrumbs_home_text' => [
          'label' => 'Breadcrumbs Home Text', 
          'description' => 'Enter the text for the home link.', 
          'attrs' => [
            'placeholder' => 'Home'
          ], 
          'enable' => 'site.breadcrumbs && site.breadcrumbs_show_home'
        ], 
        'site.main_section.height' => [
          'label' => 'Main Section Height', 
          'description' => 'On short pages, the main section can be expanded to fill the viewport. This only applies to pages which are not build with the page builder.', 
          'text' => 'Expand height', 
          'type' => 'checkbox'
        ]
      ]
    ], 
    'site-media' => [
      'title' => 'Image', 
      'width' => 400, 
      'fields' => [
        'site._image_dimension' => [
          'type' => 'grid', 
          'description' => 'Set the width and height in pixels (e.g. 600). Setting just one value preserves the original proportions. The image will be resized and cropped automatically.', 
          'width' => '1-2', 
          'fields' => [
            'site.image_width' => [
              'label' => 'Width', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ], 
            'site.image_height' => [
              'label' => 'Height', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ]
          ]
        ], 
        'site.image_size' => [
          'label' => 'Image Size', 
          'description' => 'Determine whether the image will fit the page dimensions by clipping it or by filling the empty areas with the background color.', 
          'type' => 'select', 
          'options' => [
            'Auto' => '', 
            'Cover' => 'cover', 
            'Contain' => 'contain'
          ]
        ], 
        'site.image_position' => [
          'label' => 'Image Position', 
          'description' => 'Set the initial background position, relative to the page layer.', 
          'type' => 'select', 
          'options' => [
            'Top Left' => 'top-left', 
            'Top Center' => 'top-center', 
            'Top Right' => 'top-right', 
            'Center Left' => 'center-left', 
            'Center Center' => 'center-center', 
            'Center Right' => 'center-right', 
            'Bottom Left' => 'bottom-left', 
            'Bottom Center' => 'bottom-center', 
            'Bottom Right' => 'bottom-right'
          ]
        ], 
        'site.image_effect' => [
          'label' => 'Image Effect', 
          'description' => 'Add a parallax effect or fix the background with regard to the viewport while scrolling.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Parallax' => 'parallax', 
            'Fixed' => 'fixed'
          ]
        ], 
        'site.image_parallax_bgx' => [
          'text' => 'Translate X', 
          'type' => 'parallax-stops', 
          'attrs' => [
            'min' => -600, 
            'max' => 600, 
            'step' => 10
          ], 
          'show' => 'site.image_effect == \'parallax\''
        ], 
        'site.image_parallax_bgy' => [
          'text' => 'Translate Y', 
          'type' => 'parallax-stops', 
          'attrs' => [
            'min' => -600, 
            'max' => 600, 
            'step' => 10
          ], 
          'show' => 'site.image_effect == \'parallax\''
        ], 
        'site.image_parallax_easing' => [
          'label' => 'Parallax Easing', 
          'description' => 'Set the animation easing. Zero transitions at an even speed, a negative value starts off quickly while a positive value starts off slowly.', 
          'type' => 'range', 
          'attrs' => [
            'min' => -2, 
            'max' => 2, 
            'step' => 0.1
          ], 
          'show' => 'site.image_effect == \'parallax\''
        ], 
        'site.image_parallax_breakpoint' => [
          'label' => 'Parallax Breakpoint', 
          'description' => 'Display the parallax effect only on this device width and larger.', 
          'type' => 'select', 
          'options' => [
            'Always' => '', 
            'Small (Phone Landscape)' => 's', 
            'Medium (Tablet Landscape)' => 'm', 
            'Large (Desktop)' => 'l', 
            'X-Large (Large Screens)' => 'x'
          ], 
          'show' => 'site.image_effect == \'parallax\''
        ], 
        'site.image_visibility' => [
          'label' => 'Visibility', 
          'description' => 'Display the image only on this device width and larger.', 
          'type' => 'select', 
          'options' => [
            'Always' => '', 
            'Small (Phone Landscape)' => 's', 
            'Medium (Tablet Landscape)' => 'm', 
            'Large (Desktop)' => 'l', 
            'X-Large (Large Screens)' => 'xl'
          ]
        ], 
        'site.media_background' => [
          'label' => 'Background Color', 
          'description' => 'Use the background color in combination with blend modes, a transparent image or to fill the area if the image doesn\'t cover the whole page.', 
          'type' => 'color'
        ], 
        'site.media_blend_mode' => [
          'label' => 'Blend Mode', 
          'description' => 'Determine how the image will blend with the background color.', 
          'type' => 'select', 
          'options' => [
            'Normal' => '', 
            'Multiply' => 'multiply', 
            'Screen' => 'screen', 
            'Overlay' => 'overlay', 
            'Darken' => 'darken', 
            'Lighten' => 'lighten', 
            'Color-dodge' => 'color-dodge', 
            'Color-burn' => 'color-burn', 
            'Hard-light' => 'hard-light', 
            'Soft-light' => 'soft-light', 
            'Difference' => 'difference', 
            'Exclusion' => 'exclusion', 
            'Hue' => 'hue', 
            'Saturation' => 'saturation', 
            'Color' => 'color', 
            'Luminosity' => 'luminosity'
          ]
        ], 
        'site.media_overlay' => [
          'label' => 'Overlay Color', 
          'description' => 'Set an additional transparent overlay to soften the image.', 
          'type' => 'color'
        ]
      ]
    ], 
    'header' => [
      'title' => 'Header', 
      'width' => 400, 
      'fields' => [
        'header.layout' => [
          'label' => 'Layout', 
          'description' => 'Select the layout for the <code>logo</code>, <code>navbar</code> and <code>header</code> positions. Some layouts can split or push items of a position which are shown as ellipsis.', 
          'title' => 'Select header layout', 
          'type' => 'select-img', 
          'options' => [
            'horizontal-left' => [
              'label' => 'Horizontal Left', 
              'src' => '$ASSETS/images/header/horizontal-left.svg'
            ], 
            'horizontal-center' => [
              'label' => 'Horizontal Center', 
              'src' => '$ASSETS/images/header/horizontal-center.svg'
            ], 
            'horizontal-right' => [
              'label' => 'Horizontal Right', 
              'src' => '$ASSETS/images/header/horizontal-right.svg'
            ], 
            'horizontal-justify' => [
              'label' => 'Horizontal Justify', 
              'src' => '$ASSETS/images/header/horizontal-justify.svg'
            ], 
            'horizontal-center-logo' => [
              'label' => 'Horizontal Center Logo', 
              'src' => '$ASSETS/images/header/horizontal-center-logo.svg'
            ], 
            'stacked-center-a' => [
              'label' => 'Stacked Center A', 
              'src' => '$ASSETS/images/header/stacked-center-a.svg'
            ], 
            'stacked-center-b' => [
              'label' => 'Stacked Center B', 
              'src' => '$ASSETS/images/header/stacked-center-b.svg'
            ], 
            'stacked-center-c' => [
              'label' => 'Stacked Center C', 
              'src' => '$ASSETS/images/header/stacked-center-c.svg'
            ], 
            'stacked-center-split-a' => [
              'label' => 'Stacked Center Split A', 
              'src' => '$ASSETS/images/header/stacked-center-split-a.svg'
            ], 
            'stacked-center-split-b' => [
              'label' => 'Stacked Center Split B', 
              'src' => '$ASSETS/images/header/stacked-center-split-b.svg'
            ], 
            'stacked-left' => [
              'label' => 'Stacked Left', 
              'src' => '$ASSETS/images/header/stacked-left.svg'
            ], 
            'stacked-justify' => [
              'label' => 'Stacked Justify', 
              'src' => '$ASSETS/images/header/stacked-justify.svg'
            ]
          ]
        ], 
        'header.split_index' => [
          'label' => 'Split Items', 
          'description' => 'The logo is placed automatically between the items. Optionally, set the number of items after which the items are split.', 
          'type' => 'select', 
          'options' => [
            'Auto' => '', 
            'After 1 Item' => 1, 
            'After 2 Items' => 2, 
            'After 3 Items' => 3, 
            'After 4 Items' => 4, 
            'After 5 Items' => 5, 
            'After 6 Items' => 6, 
            'After 7 Items' => 7, 
            'After 8 Items' => 8, 
            'After 9 Items' => 9, 
            'After 10 Items' => 10
          ], 
          'show' => '$match(header.layout, \'^stacked-center-(split-|c)\')'
        ], 
        'header.push_index' => [
          'label' => 'Push Items', 
          'description' => 'Set the number of items after which the following items are pushed to the right.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'After 1 Item' => 1, 
            'After 2 Items' => 2, 
            'After 3 Items' => 3, 
            'After 4 Items' => 4, 
            'After 5 Items' => 5, 
            'After 6 Items' => 6, 
            'After 7 Items' => 7, 
            'After 8 Items' => 8, 
            'After 9 Items' => 9, 
            'After 10 Items' => 10
          ], 
          'show' => '$match(header.layout, \'^stacked-left\')'
        ], 
        'header.width' => [
          'label' => 'Max Width', 
          'type' => 'select', 
          'options' => [
            'Default' => 'default', 
            'Small' => 'small', 
            'Large' => 'large', 
            'X-Large' => 'xlarge', 
            'Expand' => 'expand'
          ]
        ], 
        'header.logo_padding_remove' => [
          'description' => 'Set the maximum header width.', 
          'type' => 'checkbox', 
          'text' => 'Remove left logo padding', 
          'enable' => 'header.width == \'expand\' && !$match(header.layout, \'^stacked|^horizontal-center-logo\')'
        ], 
        'navbar.sticky' => [
          'label' => 'Navbar', 
          'description' => 'Let the navbar stick at the top of the viewport while scrolling or only when scrolling up.', 
          'type' => 'select', 
          'options' => [
            'Static' => 0, 
            'Sticky' => 1, 
            'Sticky on scroll up' => 2
          ]
        ], 
        'navbar.style' => [
          'label' => 'Navbar Style', 
          'description' => 'Select the navbar style.', 
          'type' => 'select', 
          'options' => [
            'Default' => '', 
            'Primary' => 'primary'
          ]
        ], 
        'navbar.dropdown_align' => [
          'label' => 'Dropdown', 
          'type' => 'select', 
          'options' => [
            'Left' => 'left', 
            'Right' => 'right', 
            'Center' => 'center'
          ]
        ], 
        'navbar.dropdown_target' => [
          'type' => 'checkbox', 
          'text' => 'Align to navbar'
        ], 
        'navbar.dropbar' => [
          'type' => 'checkbox', 
          'text' => 'Enable dropbar'
        ], 
        'navbar.parent_icon' => [
          'type' => 'checkbox', 
          'text' => 'Show parent icon'
        ], 
        'navbar.dropdown_click' => [
          'description' => 'Align dropdowns to their menu item or the navbar. Optionally, show them in a full-width section called dropbar, display an icon to indicate dropdowns and let text items open by click and not hover.', 
          'type' => 'checkbox', 
          'text' => 'Enable click mode on text items'
        ], 
        'dialog.layout' => [
          'label' => 'Dialog Layout', 
          'description' => 'Select the layout for the <code>dialog</code> position. Items which can be pushed are shown as ellipsis.', 
          'title' => 'Select dialog layout', 
          'type' => 'select-img', 
          'options' => [
            'dropbar-top' => [
              'label' => 'Dropbar Top', 
              'src' => '$ASSETS/images/dialog/dropbar-top.svg'
            ], 
            'dropbar-center' => [
              'label' => 'Dropbar Center', 
              'src' => '$ASSETS/images/dialog/dropbar-center.svg'
            ], 
            'offcanvas-top' => [
              'label' => 'Offcanvas Top', 
              'src' => '$ASSETS/images/dialog/offcanvas-top.svg'
            ], 
            'offcanvas-center' => [
              'label' => 'Offcanvas Center', 
              'src' => '$ASSETS/images/dialog/offcanvas-center.svg'
            ], 
            'modal-top' => [
              'label' => 'Modal Top', 
              'src' => '$ASSETS/images/dialog/modal-top.svg'
            ], 
            'modal-center' => [
              'label' => 'Modal Center', 
              'src' => '$ASSETS/images/dialog/modal-center.svg'
            ]
          ]
        ], 
        'dialog.text_center' => [
          'type' => 'checkbox', 
          'text' => 'Center horizontally'
        ], 
        'dialog.push_index' => [
          'label' => 'Dialog Push Items', 
          'description' => 'Set the number of items after which the following items are pushed to the bottom.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'After 1 Item' => 1, 
            'After 2 Items' => 2, 
            'After 3 Items' => 3, 
            'After 4 Items' => 4, 
            'After 5 Items' => 5, 
            'After 6 Items' => 6, 
            'After 7 Items' => 7, 
            'After 8 Items' => 8, 
            'After 9 Items' => 9, 
            'After 10 Items' => 10
          ]
        ], 
        'dialog.toggle' => [
          'label' => 'Dialog Toggle', 
          'type' => 'select', 
          'options' => [
            'Navbar Start' => 'navbar:start', 
            'Navbar End' => 'navbar:end', 
            'Header Start' => 'header:start', 
            'Header End' => 'header:end'
          ]
        ], 
        'dialog.toggle_text' => [
          'type' => 'checkbox', 
          'text' => 'Show the menu text next to the icon'
        ], 
        'dialog.dropbar.animation' => [
          'label' => 'Dropbar Animation', 
          'description' => 'Select the animation on how the dropbar appears below the navbar.', 
          'type' => 'select', 
          'options' => [
            'Fade' => '', 
            'Slide Top' => 'reveal-top', 
            'Slide Left' => 'slide-left', 
            'Slide Right' => 'slide-right'
          ], 
          'show' => '$match(dialog.layout, \'^dropbar\')'
        ], 
        'dialog.dropbar.width' => [
          'label' => 'Dropbar Width', 
          'description' => 'Set the dropbar width if it slides in from the left or right.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Medium' => 'medium', 
            'Large' => 'large', 
            'X-Large' => 'xlarge', 
            '2X-Large' => '2xlarge'
          ], 
          'show' => '$match(dialog.layout, \'^dropbar\')  && $match(dialog.dropbar.animation, \'^slide\')'
        ], 
        'dialog.dropbar.content_width' => [
          'label' => 'Dropbar Content Width', 
          'description' => 'Set the width of the dropbar content.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Medium' => 'width-medium', 
            'Large' => 'width-large', 
            'X-Large' => 'width-xlarge', 
            '2X-Large' => 'width-2xlarge', 
            'Container Default' => 'container', 
            'Container Small' => 'container-small', 
            'Container Large' => 'container-large', 
            'Container X-Large' => 'container-xlarge'
          ], 
          'show' => '$match(dialog.layout, \'^dropbar\')'
        ], 
        'dialog.offcanvas.mode' => [
          'label' => 'Offcanvas Mode', 
          'type' => 'select', 
          'options' => [
            'Slide' => 'slide', 
            'Reveal' => 'reveal', 
            'Push' => 'push'
          ], 
          'show' => '$match(dialog.layout, \'^offcanvas\')'
        ], 
        'dialog.offcanvas.flip' => [
          'type' => 'checkbox', 
          'text' => 'Display on the right', 
          'show' => '$match(dialog.layout, \'^offcanvas\')'
        ], 
        'dialog.offcanvas.overlay' => [
          'type' => 'checkbox', 
          'text' => 'Overlay the site', 
          'show' => '$match(dialog.layout, \'^offcanvas\')'
        ], 
        'dialog.modal.width' => [
          'label' => 'Modal Width', 
          'description' => 'Set the content width.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Small' => 'small', 
            'Medium' => 'medium', 
            'Large' => 'large', 
            'X-Large' => 'xlarge', 
            '2X-Large' => '2xlarge'
          ], 
          'show' => '$match(dialog.layout, \'^modal\')'
        ], 
        'header.search' => [
          'label' => 'Search', 
          'description' => 'Select the position that will display the search.', 
          'type' => 'select', 
          'options' => [
            'Hide' => '', 
            'Navbar Start' => 'navbar:start', 
            'Navbar End' => 'navbar:end', 
            'Header Start' => 'header:start', 
            'Header End' => 'header:end', 
            'Dialog Start' => 'dialog:start', 
            'Dialog End' => 'dialog:end', 
            'Logo End' => 'logo:end'
          ]
        ], 
        'header.search_style' => [
          'label' => 'Search Style', 
          'description' => 'Select the search style.', 
          'type' => 'select', 
          'options' => [
            'Default' => '', 
            'Modal' => 'modal'
          ], 
          'enable' => '!$match(header.search, \'^dialog\')'
        ], 
        'header.social' => [
          'label' => 'Social Icons', 
          'type' => 'select', 
          'options' => [
            'Hide' => '', 
            'Toolbar Left Start' => 'toolbar-left:start', 
            'Toolbar Left End' => 'toolbar-left:end', 
            'Toolbar Right Start' => 'toolbar-right:start', 
            'Toolbar Right End' => 'toolbar-right:end', 
            'Navbar Start' => 'navbar:start', 
            'Navbar End' => 'navbar:end', 
            'Header Start' => 'header:start', 
            'Header End' => 'header:end', 
            'Dialog Start' => 'dialog:start', 
            'Dialog End' => 'dialog:end', 
            'Logo End' => 'logo:end'
          ]
        ], 
        'header.social_links' => [
          'type' => 'button-panel', 
          'text' => 'Edit Links', 
          'panel' => 'social-links'
        ], 
        'header.social_target' => [
          'type' => 'checkbox', 
          'text' => 'Open in a new window'
        ], 
        'header.social_style' => [
          'type' => 'checkbox', 
          'text' => 'Display icons as buttons', 
          'description' => 'Select the position that will display the social icons. Be sure to add your social profile links or no icons can be displayed.'
        ], 
        'header.social_width' => [
          'label' => 'Social Icons Size', 
          'description' => 'Set the icon width.', 
          'attrs' => [
            'placeholder' => '20'
          ], 
          'show' => 'header.social'
        ], 
        'header.social_gap' => [
          'label' => 'Social Icons Gap', 
          'description' => 'Set the size of the gap between the social icons.', 
          'type' => 'select', 
          'options' => [
            'Small' => 'small', 
            'Medium' => 'medium', 
            'Default' => '', 
            'Large' => 'large', 
            'None' => 'collapse'
          ], 
          'show' => 'header.social'
        ]
      ]
    ], 
    'social-links' => [
      'title' => 'Social Links', 
      'width' => 400, 
      'fields' => [
        'header.social_links.0' => [
          'label' => 'Links', 
          'attrs' => [
            'placeholder' => 'https://'
          ]
        ], 
        'header.social_links.1' => [
          'attrs' => [
            'placeholder' => 'https://'
          ]
        ], 
        'header.social_links.2' => [
          'attrs' => [
            'placeholder' => 'https://'
          ]
        ], 
        'header.social_links.3' => [
          'attrs' => [
            'placeholder' => 'https://'
          ]
        ], 
        'header.social_links.4' => [
          'description' => 'Enter up to 5 links to your social profiles. A corresponding <a href="https://getuikit.com/docs/icon" target="_blank">UIkit brand icon</a> will be displayed automatically, if available. Links to email addresses and phone numbers, like mailto:info@example.com or tel:+491570156, are also supported.', 
          'attrs' => [
            'placeholder' => 'https://'
          ]
        ]
      ]
    ], 
    'mobile' => [
      'title' => 'Mobile', 
      'width' => 400, 
      'fields' => [
        'mobile.breakpoint' => [
          'label' => 'Visibility', 
          'description' => 'Select the device size where the default header will be replaced by the mobile header.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Small' => 's', 
            'Medium' => 'm', 
            'Large' => 'l'
          ]
        ], 
        'mobile.header.layout' => [
          'label' => 'Layout', 
          'description' => 'Select the layout for the <code>logo-mobile</code>, <code>navbar-mobile</code> and <code>header-mobile</code> positions.', 
          'title' => 'Select mobile header layout', 
          'type' => 'select-img', 
          'options' => [
            'horizontal-left' => [
              'label' => 'Horizontal Left', 
              'src' => '$ASSETS/images/mobile-header/horizontal-left.svg'
            ], 
            'horizontal-center' => [
              'label' => 'Horizontal Center', 
              'src' => '$ASSETS/images/mobile-header/horizontal-center.svg'
            ], 
            'horizontal-right' => [
              'label' => 'Horizontal Right', 
              'src' => '$ASSETS/images/mobile-header/horizontal-right.svg'
            ], 
            'horizontal-center-logo' => [
              'label' => 'Horizontal Center Logo', 
              'src' => '$ASSETS/images/mobile-header/horizontal-center-logo.svg'
            ]
          ], 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.header.transparent' => [
          'type' => 'checkbox', 
          'text' => 'Enable transparent header', 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.header.logo_padding_remove' => [
          'type' => 'checkbox', 
          'text' => 'Remove left logo padding', 
          'show' => 'mobile.breakpoint && mobile.header.layout != \'horizontal-center-logo\''
        ], 
        'mobile.navbar.sticky' => [
          'label' => 'Navbar', 
          'description' => 'Let the navbar stick at the top of the viewport while scrolling or only when scrolling up.', 
          'type' => 'select', 
          'options' => [
            'Static' => 0, 
            'Sticky' => 1, 
            'Sticky on scroll up' => 2
          ], 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.dialog.layout' => [
          'label' => 'Dialog Layout', 
          'description' => 'Select the layout for the <code>dialog-mobile</code> position. Pushed items of the position are shown as ellipsis.', 
          'title' => 'Select mobile dialog layout', 
          'type' => 'select-img', 
          'options' => [
            'dropbar-top' => [
              'label' => 'Dropbar Top', 
              'src' => '$ASSETS/images/mobile-dialog/dropbar-top.svg'
            ], 
            'dropbar-center' => [
              'label' => 'Dropbar Center', 
              'src' => '$ASSETS/images/mobile-dialog/dropbar-center.svg'
            ], 
            'offcanvas-top' => [
              'label' => 'Offcanvas Top', 
              'src' => '$ASSETS/images/mobile-dialog/offcanvas-top.svg'
            ], 
            'offcanvas-center' => [
              'label' => 'Offcanvas Center', 
              'src' => '$ASSETS/images/mobile-dialog/offcanvas-center.svg'
            ], 
            'modal-top' => [
              'label' => 'Modal Top', 
              'src' => '$ASSETS/images/mobile-dialog/modal-top.svg'
            ], 
            'modal-center' => [
              'label' => 'Modal Center', 
              'src' => '$ASSETS/images/mobile-dialog/modal-center.svg'
            ]
          ], 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.dialog.text_center' => [
          'type' => 'checkbox', 
          'text' => 'Center horizontally', 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.dialog.close' => [
          'type' => 'checkbox', 
          'text' => 'Show close button', 
          'show' => 'mobile.breakpoint && $match(mobile.dialog.layout, \'^offcanvas|^modal\')'
        ], 
        'mobile.dialog.push_index' => [
          'label' => 'Dialog Push Items', 
          'description' => 'Set the number of items after which the following items are pushed to the bottom.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'After 1 Item' => 1, 
            'After 2 Items' => 2, 
            'After 3 Items' => 3, 
            'After 4 Items' => 4, 
            'After 5 Items' => 5, 
            'After 6 Items' => 6, 
            'After 7 Items' => 7, 
            'After 8 Items' => 8, 
            'After 9 Items' => 9, 
            'After 10 Items' => 10
          ], 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.dialog.toggle' => [
          'label' => 'Dialog Toggle', 
          'type' => 'select', 
          'options' => [
            'Navbar Start' => 'navbar-mobile:start', 
            'Navbar End' => 'navbar-mobile:end', 
            'Header Start' => 'header-mobile:start', 
            'Header End' => 'header-mobile:end'
          ], 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.dialog.toggle_text' => [
          'type' => 'checkbox', 
          'text' => 'Show the menu text next to the icon', 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.dialog.offcanvas.mode' => [
          'label' => 'Offcanvas Mode', 
          'type' => 'select', 
          'options' => [
            'Slide' => 'slide', 
            'Reveal' => 'reveal', 
            'Push' => 'push'
          ], 
          'show' => 'mobile.breakpoint && $match(mobile.dialog.layout, \'^offcanvas\')'
        ], 
        'mobile.dialog.offcanvas.flip' => [
          'type' => 'checkbox', 
          'text' => 'Display on the right', 
          'show' => 'mobile.breakpoint && $match(mobile.dialog.layout, \'^offcanvas\')'
        ], 
        'mobile.dialog.modal.width' => [
          'label' => 'Modal Width', 
          'description' => 'Set the content width.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Small' => 'small', 
            'Medium' => 'medium', 
            'Large' => 'large', 
            'X-Large' => 'xlarge', 
            '2X-Large' => '2xlarge'
          ], 
          'show' => 'mobile.breakpoint && $match(mobile.dialog.layout, \'^modal\')'
        ], 
        'mobile.dialog.dropbar.animation' => [
          'label' => 'Dropbar Animation', 
          'description' => 'Select the animation on how the dropbar appears below the navbar.', 
          'type' => 'select', 
          'options' => [
            'Fade' => '', 
            'Slide Top' => 'reveal-top', 
            'Slide Left' => 'slide-left', 
            'Slide Right' => 'slide-right'
          ], 
          'show' => 'mobile.breakpoint && $match(mobile.dialog.layout, \'^dropbar\')'
        ], 
        'mobile.header.search' => [
          'label' => 'Search', 
          'description' => 'Select the position that will display the search.', 
          'type' => 'select', 
          'options' => [
            'Hide' => '', 
            'Navbar Start' => 'navbar-mobile:start', 
            'Navbar End' => 'navbar-mobile:end', 
            'Header Start' => 'header-mobile:start', 
            'Header End' => 'header-mobile:end', 
            'Dialog Start' => 'dialog-mobile:start', 
            'Dialog End' => 'dialog-mobile:end', 
            'Logo End' => 'logo-mobile:end'
          ], 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.header.search_style' => [
          'label' => 'Search Style', 
          'description' => 'Select the search style.', 
          'type' => 'select', 
          'options' => [
            'Default' => '', 
            'Modal' => 'modal'
          ], 
          'show' => 'mobile.breakpoint', 
          'enable' => '!$match(mobile.header.search, \'^dialog\')'
        ], 
        'mobile.header.social' => [
          'label' => 'Social Icons', 
          'type' => 'select', 
          'options' => [
            'Hide' => '', 
            'Navbar Start' => 'navbar-mobile:start', 
            'Navbar End' => 'navbar-mobile:end', 
            'Header Start' => 'header-mobile:start', 
            'Header End' => 'header-mobile:end', 
            'Dialog Start' => 'dialog-mobile:start', 
            'Dialog End' => 'dialog-mobile:end', 
            'Logo End' => 'logo-mobile:end'
          ], 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.header.social_links' => [
          'type' => 'button-panel', 
          'text' => 'Edit Links', 
          'panel' => 'mobile.social-links', 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.header.social_target' => [
          'type' => 'checkbox', 
          'text' => 'Open in a new window', 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.header.social_style' => [
          'type' => 'checkbox', 
          'text' => 'Display icons as buttons', 
          'description' => 'Select the position that will display the social icons. Be sure to add your social profile links or no icons can be displayed.', 
          'show' => 'mobile.breakpoint'
        ], 
        'mobile.header.social_width' => [
          'label' => 'Social Icons Size', 
          'description' => 'Set the icon width.', 
          'attrs' => [
            'placeholder' => '20'
          ], 
          'show' => 'mobile.breakpoint && mobile.header.social'
        ], 
        'mobile.header.social_gap' => [
          'label' => 'Social Icons Gap', 
          'description' => 'Set the size of the gap between the social icons.', 
          'type' => 'select', 
          'options' => [
            'Small' => 'small', 
            'Medium' => 'medium', 
            'Default' => '', 
            'Large' => 'large', 
            'None' => 'collapse'
          ], 
          'show' => 'mobile.breakpoint && mobile.header.social'
        ]
      ]
    ], 
    'mobile.social-links' => [
      'title' => 'Social Links', 
      'width' => 400, 
      'fields' => [
        'mobile.header.social_links.0' => [
          'label' => 'Links', 
          'attrs' => [
            'placeholder' => 'https://'
          ]
        ], 
        'mobile.header.social_links.1' => [
          'attrs' => [
            'placeholder' => 'https://'
          ]
        ], 
        'mobile.header.social_links.2' => [
          'attrs' => [
            'placeholder' => 'https://'
          ]
        ], 
        'mobile.header.social_links.3' => [
          'attrs' => [
            'placeholder' => 'https://'
          ]
        ], 
        'mobile.header.social_links.4' => [
          'description' => 'Enter up to 5 links to your social profiles. A corresponding <a href="https://getuikit.com/docs/icon" target="_blank">UIkit brand icon</a> will be displayed automatically, if available. Links to email addresses and phone numbers, like mailto:info@example.com or tel:+491570156, are also supported.', 
          'attrs' => [
            'placeholder' => 'https://'
          ]
        ]
      ]
    ], 
    'menu-item' => [
      'title' => 'Menu Item', 
      'width' => 400, 
      'fields' => [
        'image' => [
          'label' => 'Image', 
          'type' => 'image', 
          'enable' => '!icon'
        ], 
        'icon' => [
          'label' => 'Icon', 
          'description' => 'Instead of using a custom image, you can click on the pencil to pick an icon from the icon library.', 
          'type' => 'icon', 
          'enable' => '!image'
        ], 
        'image_only' => [
          'label' => 'Image and Title', 
          'description' => 'Only show the image or icon.', 
          'type' => 'checkbox', 
          'text' => 'Hide title', 
          'enable' => 'image || icon'
        ], 
        'subtitle' => [
          'label' => 'Subtitle', 
          'description' => 'Enter a subtitle that will be displayed beneath the nav item.', 
          'type' => 'text', 
          'enable' => '!((image || icon) && image_only)'
        ], 
        'dropdown.columns' => [
          'label' => 'Dropdown Columns', 
          'description' => 'Split the dropdown into columns.', 
          'type' => 'select', 
          'default' => 1, 
          'options' => [
            '1 Column' => 1, 
            '2 Columns' => 2, 
            '3 Columns' => 3, 
            '4 Columns' => 4, 
            '5 Columns' => 5
          ], 
          'show' => 'this.panel.item.level == 0 && !content'
        ], 
        'dropdown.stretch' => [
          'label' => 'Dropdown Stretch', 
          'description' => 'Stretch the dropdown to the width of the navbar or the navbar container.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Navbar' => 'navbar', 
            'Navbar Container' => 'navbar-container'
          ], 
          'show' => 'this.panel.item.level == 0'
        ], 
        'dropdown.width' => [
          'label' => 'Dropdown Width', 
          'description' => 'Set the dropdown width in pixels (e.g. 600).', 
          'show' => 'this.panel.item.level == 0', 
          'enable' => '!dropdown.justify'
        ], 
        'dropdown.size' => [
          'label' => 'Dropdown Padding', 
          'type' => 'checkbox', 
          'text' => 'Large padding', 
          'show' => 'this.panel.item.level == 0'
        ], 
        'dropdown.padding_remove_horizontal' => [
          'type' => 'checkbox', 
          'text' => 'Remove horizontal padding', 
          'show' => 'this.panel.item.level == 0 && content'
        ], 
        'dropdown.padding_remove_vertical' => [
          'type' => 'checkbox', 
          'text' => 'Remove vertical padding', 
          'show' => 'this.panel.item.level == 0 && content'
        ], 
        'dropdown.align' => [
          'label' => 'Dropdown Alignment', 
          'type' => 'select', 
          'options' => [
            'Default' => '', 
            'Left' => 'left', 
            'Right' => 'right', 
            'Center' => 'center'
          ], 
          'show' => 'this.panel.item.level == 0', 
          'enable' => '!dropdown.justify'
        ], 
        'dropdown.nav_style' => [
          'label' => 'Dropdown Nav Style', 
          'description' => 'Select the nav style.', 
          'type' => 'select', 
          'options' => [
            'Default' => '', 
            'Secondary' => 'secondary'
          ], 
          'show' => 'this.panel.item.level == 0 && !content'
        ]
      ]
    ], 
    'menu-position' => [
      'title' => 'Position', 
      'width' => 400, 
      'fields' => [
        'type' => [
          'label' => 'Type', 
          'type' => 'select', 
          'options' => [
            'Default' => '', 
            'Nav' => 'nav', 
            'Subnav' => 'subnav', 
            'Iconnav' => 'iconnav'
          ]
        ], 
        'divider' => [
          'description' => 'Select an alternative menu type and show optional dividers between nav or subnav items.', 
          'type' => 'checkbox', 
          'text' => 'Show dividers'
        ], 
        'style' => [
          'label' => 'Style', 
          'description' => 'Select the nav style.', 
          'type' => 'select', 
          'options' => [
            'Default' => 'default', 
            'Primary' => 'primary', 
            'Secondary' => 'secondary'
          ], 
          'show' => '$match(this.panel.position, \'^dialog\')'
        ], 
        '_image_dimensions' => [
          'type' => 'grid', 
          'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
          'width' => '1-2', 
          'fields' => [
            'image_width' => [
              'label' => 'Image Width', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ], 
            'image_height' => [
              'label' => 'Image Height', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ]
          ]
        ], 
        'image_svg_inline' => [
          'label' => 'Inline SVG', 
          'description' => 'Inject SVG images into the markup so they adopt the text color automatically.', 
          'type' => 'checkbox', 
          'text' => 'Make SVG stylable with CSS'
        ], 
        'icon_width' => [
          'label' => 'Icon Width', 
          'description' => 'Set the icon width.'
        ], 
        'image_margin' => [
          'label' => 'Image and Title', 
          'type' => 'checkbox', 
          'text' => 'Add margin between'
        ], 
        'image_align' => [
          'label' => 'Image Align', 
          'type' => 'select', 
          'options' => [
            'Top' => 'top', 
            'Center' => 'center'
          ]
        ]
      ]
    ], 
    'top' => [
      'title' => 'Top', 
      'width' => 400, 
      'fields' => [
        'top.style' => [
          'label' => 'Style', 
          'type' => 'select', 
          'options' => [
            'Default' => 'default', 
            'Muted' => 'muted', 
            'Primary' => 'primary', 
            'Secondary' => 'secondary'
          ]
        ], 
        'top.overlap' => [
          'type' => 'checkbox', 
          'description' => 'Sections will only overlap each other, if it\'s supported by the style. Otherwise it has no visual effect.', 
          'text' => 'Overlap the following section'
        ], 
        'top.image' => [
          'label' => 'Image', 
          'description' => 'Upload a background image.', 
          'type' => 'image', 
          'show' => '!top.video'
        ], 
        'top.video' => [
          'label' => 'Video', 
          'description' => 'Select a video file or enter a link from <a href="https://www.youtube.com" target="_blank">YouTube</a> or <a href="https://vimeo.com" target="_blank">Vimeo</a>.', 
          'type' => 'video', 
          'show' => '!top.image'
        ], 
        'top.media' => [
          'type' => 'button-panel', 
          'text' => 'Edit Settings', 
          'panel' => 'top-media', 
          'show' => 'top.image || top.video'
        ], 
        'top.preserve_color' => [
          'label' => 'Text Color', 
          'description' => 'Disable automatic text recoloring, for example when you use cards inside sections.', 
          'type' => 'checkbox', 
          'text' => 'Preserve color', 
          'show' => '$match(top.style, \'primary|secondary\')'
        ], 
        'top.text_color' => [
          'label' => 'Text Color', 
          'description' => 'Set light or dark color mode for text, buttons and controls.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Light' => 'light', 
            'Dark' => 'dark'
          ], 
          'show' => '!$match(top.style, \'primary|secondary\') && (top.image || top.video)'
        ], 
        'top.width' => [
          'label' => 'Max Width', 
          'description' => 'Set the maximum content width.', 
          'type' => 'select', 
          'options' => [
            'Default' => 'default', 
            'Small' => 'small', 
            'Large' => 'large', 
            'X-Large' => 'xlarge', 
            'Expand' => 'expand', 
            'None' => ''
          ]
        ], 
        'top.height' => [
          'label' => 'Height', 
          'description' => 'Enabling viewport height on a section that directly follows the header will subtract the header\'s height from it. On short pages, a section can be expanded to fill the viewport.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Viewport' => 'full', 
            'Viewport (Minus 20%)' => 'percent', 
            'Viewport (Minus the following section)' => 'section', 
            'Expand' => 'expand'
          ]
        ], 
        'top.padding' => [
          'label' => 'Padding', 
          'description' => 'Set the vertical padding.', 
          'type' => 'select', 
          'options' => [
            'Default' => '', 
            'X-Small' => 'xsmall', 
            'Small' => 'small', 
            'Large' => 'large', 
            'X-Large' => 'xlarge', 
            'None' => 'none'
          ]
        ], 
        'top.padding_remove_top' => [
          'type' => 'checkbox', 
          'text' => 'Remove top padding', 
          'enable' => 'top.padding != \'none\''
        ], 
        'top.padding_remove_bottom' => [
          'type' => 'checkbox', 
          'text' => 'Remove bottom padding', 
          'enable' => 'top.padding != \'none\''
        ], 
        'top.header_transparent' => [
          'label' => 'Transparent Header', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Overlay (Light)' => 'light', 
            'Overlay (Dark)' => 'dark'
          ]
        ], 
        'top.header_transparent_noplaceholder' => [
          'description' => 'Make the header transparent and overlay the section background. Select dark or light text. Note: This only applies, if the section directly follows the header.', 
          'type' => 'checkbox', 
          'text' => 'Pull content beneath navbar', 
          'enable' => 'top.header_transparent'
        ], 
        'top.column_gap' => [
          'label' => 'Column Gap', 
          'description' => 'Set the size of the gap between the grid columns.', 
          'type' => 'select', 
          'options' => [
            'Small' => 'small', 
            'Medium' => 'medium', 
            'Default' => '', 
            'Large' => 'large', 
            'None' => 'collapse'
          ]
        ], 
        'top.row_gap' => [
          'label' => 'Row Gap', 
          'description' => 'Set the size of the gap between the grid rows.', 
          'type' => 'select', 
          'options' => [
            'Small' => 'small', 
            'Medium' => 'medium', 
            'Default' => '', 
            'Large' => 'large', 
            'None' => 'collapse'
          ]
        ], 
        'top.divider' => [
          'label' => 'Divider', 
          'description' => 'Show a divider between grid columns.', 
          'type' => 'checkbox', 
          'text' => 'Show dividers', 
          'enable' => 'top.column_gap != \'collapse\' && top.row_gap != \'collapse\''
        ], 
        'top.vertical_align' => [
          'label' => 'Vertical Alignment', 
          'description' => 'Align the section content vertically, if the section height is larger than the content itself.', 
          'type' => 'select', 
          'options' => [
            'Top' => '', 
            'Middle' => 'middle', 
            'Bottom' => 'bottom'
          ], 
          'show' => '$match(top.height, \'full|percent|section\')'
        ], 
        'top.match' => [
          'label' => 'Panels', 
          'description' => 'Stretch the panel to match the height of the grid cell.', 
          'type' => 'checkbox', 
          'text' => 'Match height'
        ], 
        'top.breakpoint' => [
          'label' => 'Breakpoint', 
          'description' => 'Set the breakpoint from which grid items will stack.', 
          'type' => 'select', 
          'options' => [
            'Small (Phone Landscape)' => 's', 
            'Medium (Tablet Landscape)' => 'm', 
            'Large (Desktop)' => 'l', 
            'X-Large (Large Screens)' => 'xl'
          ]
        ]
      ]
    ], 
    'top-media' => [
      'title' => 'Image/Video', 
      'width' => 400, 
      'fields' => [
        'top._image_dimension' => [
          'type' => 'grid', 
          'description' => 'Set the width and height in pixels (e.g. 600). Setting just one value preserves the original proportions. The image will be resized and cropped automatically.', 
          'width' => '1-2', 
          'fields' => [
            'top.image_width' => [
              'label' => 'Width', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ], 
            'top.image_height' => [
              'label' => 'Height', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ]
          ], 
          'show' => 'top.image && !top.video'
        ], 
        'top.image_size' => [
          'label' => 'Image Size', 
          'description' => 'Determine whether the image will fit the section dimensions by clipping it or by filling the empty areas with the background color.', 
          'type' => 'select', 
          'options' => [
            'Auto' => '', 
            'Cover' => 'cover', 
            'Contain' => 'contain'
          ], 
          'show' => 'top.image && !top.video'
        ], 
        'top.image_position' => [
          'label' => 'Image Position', 
          'description' => 'Set the initial background position, relative to the section layer.', 
          'type' => 'select', 
          'options' => [
            'Top Left' => 'top-left', 
            'Top Center' => 'top-center', 
            'Top Right' => 'top-right', 
            'Center Left' => 'center-left', 
            'Center Center' => 'center-center', 
            'Center Right' => 'center-right', 
            'Bottom Left' => 'bottom-left', 
            'Bottom Center' => 'bottom-center', 
            'Bottom Right' => 'bottom-right'
          ], 
          'show' => 'top.image && !top.video'
        ], 
        'top.image_fixed' => [
          'label' => 'Image Attachment', 
          'text' => 'Fix the background with regard to the viewport.', 
          'type' => 'checkbox', 
          'show' => 'top.image && !top.video'
        ], 
        'top.image_visibility' => [
          'label' => 'Visibility', 
          'description' => 'Display the image only on this device width and larger.', 
          'type' => 'select', 
          'options' => [
            'Always' => '', 
            'Small (Phone Landscape)' => 's', 
            'Medium (Tablet Landscape)' => 'm', 
            'Large (Desktop)' => 'l', 
            'X-Large (Large Screens)' => 'xl'
          ], 
          'show' => 'top.image && !top.video'
        ], 
        'top._video_dimension' => [
          'type' => 'grid', 
          'description' => 'Set the video dimensions.', 
          'width' => '1-2', 
          'fields' => [
            'video_width' => [
              'label' => 'Width'
            ], 
            'video_height' => [
              'label' => 'Height'
            ]
          ], 
          'show' => 'top.video && !top.image'
        ], 
        'top.media_background' => [
          'label' => 'Background Color', 
          'description' => 'Use the background color in combination with blend modes, a transparent image or to fill the area, if the image doesn\'t cover the whole section.', 
          'type' => 'color'
        ], 
        'top.media_blend_mode' => [
          'label' => 'Blend Mode', 
          'description' => 'Determine how the image or video will blend with the background color.', 
          'type' => 'select', 
          'options' => [
            'Normal' => '', 
            'Multiply' => 'multiply', 
            'Screen' => 'screen', 
            'Overlay' => 'overlay', 
            'Darken' => 'darken', 
            'Lighten' => 'lighten', 
            'Color-dodge' => 'color-dodge', 
            'Color-burn' => 'color-burn', 
            'Hard-light' => 'hard-light', 
            'Soft-light' => 'soft-light', 
            'Difference' => 'difference', 
            'Exclusion' => 'exclusion', 
            'Hue' => 'hue', 
            'Saturation' => 'saturation', 
            'Color' => 'color', 
            'Luminosity' => 'luminosity'
          ]
        ], 
        'top.media_overlay' => [
          'label' => 'Overlay Color', 
          'description' => 'Set an additional transparent overlay to soften the image or video.', 
          'type' => 'color'
        ]
      ]
    ], 
    'sidebar' => [
      'title' => 'Sidebar', 
      'width' => 400, 
      'fields' => [
        'main_sidebar.width' => [
          'label' => 'Width', 
          'description' => 'Set a sidebar width in percent and the content column will adjust accordingly. The width will not go below the Sidebar\'s min-width, which you can set in the Style section.', 
          'type' => 'select', 
          'options' => [
            '20%' => '1-5', 
            '25%' => '1-4', 
            '33%' => '1-3', 
            '40%' => '2-5', 
            '50%' => '1-2'
          ]
        ], 
        'main_sidebar.breakpoint' => [
          'label' => 'Breakpoint', 
          'description' => 'Set the breakpoint from which the sidebar and content will stack.', 
          'type' => 'select', 
          'options' => [
            'Small (Phone Landscape)' => 's', 
            'Medium (Tablet Landscape)' => 'm', 
            'Large (Desktop)' => 'l'
          ]
        ], 
        'main_sidebar.first' => [
          'label' => 'Order', 
          'type' => 'checkbox', 
          'text' => 'Move the sidebar to the left of the content'
        ], 
        'main_sidebar.gutter' => [
          'label' => 'Gap', 
          'description' => 'Set the padding between sidebar and content.', 
          'type' => 'select', 
          'options' => [
            'Default' => '', 
            'Small' => 'small', 
            'Large' => 'large', 
            'None' => 'collapse'
          ]
        ], 
        'main_sidebar.divider' => [
          'label' => 'Divider', 
          'type' => 'checkbox', 
          'text' => 'Display a divider between sidebar and content'
        ]
      ]
    ], 
    'bottom' => [
      'title' => 'Bottom', 
      'width' => 400, 
      'fields' => [
        'bottom.style' => [
          'label' => 'Style', 
          'type' => 'select', 
          'options' => [
            'Default' => 'default', 
            'Muted' => 'muted', 
            'Primary' => 'primary', 
            'Secondary' => 'secondary'
          ]
        ], 
        'bottom.overlap' => [
          'type' => 'checkbox', 
          'description' => 'Sections will only overlap each other, if it\'s supported by the style. Otherwise it has no visual effect.', 
          'text' => 'Overlap the following section'
        ], 
        'bottom.image' => [
          'label' => 'Image', 
          'description' => 'Upload a background image.', 
          'type' => 'image', 
          'show' => '!bottom.video'
        ], 
        'bottom.video' => [
          'label' => 'Video', 
          'description' => 'Select a video file or enter a link from <a href="https://www.youtube.com" target="_blank">YouTube</a> or <a href="https://vimeo.com" target="_blank">Vimeo</a>.', 
          'type' => 'video', 
          'show' => '!bottom.image'
        ], 
        'bottom.media' => [
          'type' => 'button-panel', 
          'text' => 'Edit Settings', 
          'panel' => 'bottom-media', 
          'show' => 'bottom.image || bottom.video'
        ], 
        'bottom.preserve_color' => [
          'label' => 'Text Color', 
          'description' => 'Disable automatic text recoloring, for example when you use cards inside sections.', 
          'type' => 'checkbox', 
          'text' => 'Preserve color', 
          'show' => '$match(bottom.style, \'primary|secondary\')'
        ], 
        'bottom.text_color' => [
          'label' => 'Text Color', 
          'description' => 'Set light or dark color mode for text, buttons and controls.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Light' => 'light', 
            'Dark' => 'dark'
          ], 
          'show' => '!$match(bottom.style, \'primary|secondary\') && (bottom.image || bottom.video)'
        ], 
        'bottom.width' => [
          'label' => 'Max Width', 
          'description' => 'Set the maximum content width.', 
          'type' => 'select', 
          'options' => [
            'Default' => 'default', 
            'Small' => 'small', 
            'Large' => 'large', 
            'X-Large' => 'xlarge', 
            'Expand' => 'expand', 
            'None' => ''
          ]
        ], 
        'bottom.height' => [
          'label' => 'Height', 
          'description' => 'Enabling viewport height on a section that directly follows the header will subtract the header\'s height from it. On short pages, a section can be expanded to fill the viewport.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Viewport' => 'full', 
            'Viewport (Minus 20%)' => 'percent', 
            'Viewport (Minus the following section)' => 'section', 
            'Expand' => 'expand'
          ]
        ], 
        'bottom.padding' => [
          'label' => 'Padding', 
          'description' => 'Set the vertical padding.', 
          'type' => 'select', 
          'options' => [
            'Default' => '', 
            'X-Small' => 'xsmall', 
            'Small' => 'small', 
            'Large' => 'large', 
            'X-Large' => 'xlarge', 
            'None' => 'none'
          ]
        ], 
        'bottom.padding_remove_top' => [
          'type' => 'checkbox', 
          'text' => 'Remove top padding', 
          'enable' => 'bottom.padding != \'none\''
        ], 
        'bottom.padding_remove_bottom' => [
          'type' => 'checkbox', 
          'text' => 'Remove bottom padding', 
          'enable' => 'bottom.padding != \'none\''
        ], 
        'bottom.column_gap' => [
          'label' => 'Column Gap', 
          'description' => 'Set the size of the gap between the grid columns.', 
          'type' => 'select', 
          'options' => [
            'Small' => 'small', 
            'Medium' => 'medium', 
            'Default' => '', 
            'Large' => 'large', 
            'None' => 'collapse'
          ]
        ], 
        'bottom.row_gap' => [
          'label' => 'Row Gap', 
          'description' => 'Set the size of the gap between the grid rows.', 
          'type' => 'select', 
          'options' => [
            'Small' => 'small', 
            'Medium' => 'medium', 
            'Default' => '', 
            'Large' => 'large', 
            'None' => 'collapse'
          ]
        ], 
        'bottom.divider' => [
          'label' => 'Divider', 
          'description' => 'Show a divider between grid columns.', 
          'type' => 'checkbox', 
          'text' => 'Show dividers', 
          'enable' => 'bottom.column_gap != \'collapse\' && bottom.row_gap != \'collapse\''
        ], 
        'bottom.vertical_align' => [
          'label' => 'Vertical Alignment', 
          'description' => 'Align the section content vertically, if the section height is larger than the content itself.', 
          'type' => 'select', 
          'options' => [
            'Top' => '', 
            'Middle' => 'middle', 
            'Bottom' => 'bottom'
          ], 
          'show' => '$match(bottom.height, \'full|percent|section\')'
        ], 
        'bottom.match' => [
          'label' => 'Panels', 
          'description' => 'Stretch the panel to match the height of the grid cell.', 
          'type' => 'checkbox', 
          'text' => 'Match height'
        ], 
        'bottom.breakpoint' => [
          'label' => 'Breakpoint', 
          'description' => 'Set the breakpoint from which grid items will stack.', 
          'type' => 'select', 
          'options' => [
            'Small (Phone Landscape)' => 's', 
            'Medium (Tablet Landscape)' => 'm', 
            'Large (Desktop)' => 'l', 
            'X-Large (Large Screens)' => 'xl'
          ]
        ]
      ]
    ], 
    'bottom-media' => [
      'title' => 'Image/Video', 
      'width' => 400, 
      'fields' => [
        'bottom._image_dimension' => [
          'type' => 'grid', 
          'description' => 'Set the width and height in pixels (e.g. 600). Setting just one value preserves the original proportions. The image will be resized and cropped automatically.', 
          'width' => '1-2', 
          'fields' => [
            'bottom.image_width' => [
              'label' => 'Width', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ], 
            'bottom.image_height' => [
              'label' => 'Height', 
              'attrs' => [
                'placeholder' => 'auto'
              ]
            ]
          ], 
          'show' => 'bottom.image && !bottom.video'
        ], 
        'bottom.image_size' => [
          'label' => 'Image Size', 
          'description' => 'Determine whether the image will fit the section dimensions by clipping it or by filling the empty areas with the background color.', 
          'type' => 'select', 
          'options' => [
            'Auto' => '', 
            'Cover' => 'cover', 
            'Contain' => 'contain'
          ], 
          'show' => 'bottom.image && !bottom.video'
        ], 
        'bottom.image_position' => [
          'label' => 'Image Position', 
          'description' => 'Set the initial background position, relative to the section layer.', 
          'type' => 'select', 
          'options' => [
            'Top Left' => 'top-left', 
            'Top Center' => 'top-center', 
            'Top Right' => 'top-right', 
            'Center Left' => 'center-left', 
            'Center Center' => 'center-center', 
            'Center Right' => 'center-right', 
            'Bottom Left' => 'bottom-left', 
            'Bottom Center' => 'bottom-center', 
            'Bottom Right' => 'bottom-right'
          ], 
          'show' => 'bottom.image && !bottom.video'
        ], 
        'bottom.image_fixed' => [
          'label' => 'Image Attachment', 
          'text' => 'Fix the background with regard to the viewport.', 
          'type' => 'checkbox', 
          'show' => 'bottom.image && !bottom.video'
        ], 
        'bottom.image_visibility' => [
          'label' => 'Visibility', 
          'description' => 'Display the image only on this device width and larger.', 
          'type' => 'select', 
          'options' => [
            'Always' => '', 
            'Small (Phone Landscape)' => 's', 
            'Medium (Tablet Landscape)' => 'm', 
            'Large (Desktop)' => 'l', 
            'X-Large (Large Screens)' => 'xl'
          ], 
          'show' => 'bottom.image && !bottom.video'
        ], 
        'bottom._video_dimension' => [
          'type' => 'grid', 
          'description' => 'Set the video dimensions.', 
          'width' => '1-2', 
          'fields' => [
            'video_width' => [
              'label' => 'Width'
            ], 
            'video_height' => [
              'label' => 'Height'
            ]
          ], 
          'show' => 'bottom.video && !bottom.image'
        ], 
        'bottom.media_background' => [
          'label' => 'Background Color', 
          'description' => 'Use the background color in combination with blend modes, a transparent image or to fill the area, if the image doesn\'t cover the whole section.', 
          'type' => 'color'
        ], 
        'bottom.media_blend_mode' => [
          'label' => 'Blend Mode', 
          'description' => 'Determine how the image or video will blend with the background color.', 
          'type' => 'select', 
          'options' => [
            'Normal' => '', 
            'Multiply' => 'multiply', 
            'Screen' => 'screen', 
            'Overlay' => 'overlay', 
            'Darken' => 'darken', 
            'Lighten' => 'lighten', 
            'Color-dodge' => 'color-dodge', 
            'Color-burn' => 'color-burn', 
            'Hard-light' => 'hard-light', 
            'Soft-light' => 'soft-light', 
            'Difference' => 'difference', 
            'Exclusion' => 'exclusion', 
            'Hue' => 'hue', 
            'Saturation' => 'saturation', 
            'Color' => 'color', 
            'Luminosity' => 'luminosity'
          ]
        ], 
        'bottom.media_overlay' => [
          'label' => 'Overlay Color', 
          'description' => 'Set an additional transparent overlay to soften the image or video.', 
          'type' => 'color'
        ]
      ]
    ], 
    'footer-builder' => [
      'title' => 'Footer', 
      'heading' => false, 
      'width' => 500
    ]
  ]
];
