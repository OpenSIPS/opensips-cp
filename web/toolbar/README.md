# Opensips custom **toolbar** - Network Lab

This toolbar help to use more friendly all tables(module) of the [OpenSIPS-CP](https://github.com/OpenSIPS/opensips-cp)

## Features

*   Add Extra column
*   Hide/Show any data.
*   Shorting by wants data.
*   Filtering.

## Getting started
1.  Copy this **toolbar** in `opensips-cp/web` folder
1.  The **toolbar** will required **jQuery** and may add in `<head>` in `web/main.php`.

        <script src="/toolbar/js/vendor/jquery.min.js"></script>

1.  To load the **toolbar**,  must add under code inside `onXloadfunction()`. [here](https://github.com).

        <script>
            function onXloadfunction() {

                var path = top.frames['main_body'].location.pathname;
                var items = path.split('/');
                if (items.length > 4 && items[items.length - 4] == "tools") {
                    var tool = items[items.length - 2];
                    var section = items[items.length - 3];
                    top.frames['main_menu'].UpdateWholeMenu(tool);
                }

                <!--   @ntlToolbar  -->
                <?php if (!empty($config->ntl_toolbar) && $config->ntl_toolbar):?>

                <?php $_SESSION['ntl_toolbar'] = $config->ntl_toolbar;?>

                try {

                    $('head', window.frames['main_body'].document).append('<!--   @ntlToolbar  -->');
                    $('head', window.frames['main_body'].document).append('<link rel="stylesheet" href="/toolbar/css/vendor/jquery.dataTables.min.css">');
                    $('head', window.frames['main_body'].document).append('<link rel="stylesheet" href="/toolbar/css/vendor/bootstrap.min.css">');
                    $('head', window.frames['main_body'].document).append('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">');
                    $('head', window.frames['main_body'].document).append('<link rel="stylesheet" href="/toolbar/css/toolbar.css?v=1.56">');

                    $('head', window.frames['main_body'].document).append($('<script>').attr('src', '/toolbar/js/vendor/jquery.min.js'));
                    $('head', window.frames['main_body'].document).append($('<script>').text("" +

                        "$('.ttable').hide();" +
                        "$('.ttable').parent().addClass('spinner');\n" +
                        "activeModule = '" + tool + "';" +
                        "extraColumn = '<?php echo(!empty($config->extra_column) ? $config->extra_column : 3);?>';"));

                    $('head', window.frames['main_body'].document).append('<!--   @ntlToolbar  -->');

                    $('html', window.frames['main_body'].document).append("<footer></footer>");
                    $('footer', window.frames['main_body'].document).append('<!--   @ntlToolbar  -->');

                    $('footer', window.frames['main_body'].document).append($('<script>').attr('src', '/toolbar/js/vendor/bootstrap.min.js'));
                    $('footer', window.frames['main_body'].document).append($('<script>').attr('src', '/toolbar/js/vendor/jquery.dataTables.min.js'));

                    $('footer', window.frames['main_body'].document).append($('<script>').attr('src', '/toolbar/js/toolbar.js?v=1.315'));
                    $('footer', window.frames['main_body'].document).append('<!--   @ntlToolbar  -->');

                } catch (e) {
                    alert(e + " Please check install guide. https://netlab.com/opensips/toolbar.com");
                    window.location.reload();
                }
                <?php endif;?>
            }
        </script>

1. Set **toolbar** config in `globals.php` in root config.

        $config->ntl_toolbar = true;
        $config->extra_column = 3;
    
    `ntl_toolbar` set **True**. If need to disable **toolbar**, set **False** .
    `extra_column` default 3 : Maximum number of extra column 
1. To enable **toolbar** in table of your choice, must add `<thead>` tag and remove all colspan.  ex: `echo($no_result);`. [here](https://github.com)

### Support forum


### Authors

