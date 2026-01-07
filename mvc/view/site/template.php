<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//  error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Limpa a flag de atualização de índice para que o banner possa atualizar o índice GLOBAL
// nesta nova requisição de página.
if (isset($_SESSION['banner_index_updated_on_this_load'])) {
    unset($_SESSION['banner_index_updated_on_this_load']);
}


require($_SERVER['DOCUMENT_ROOT'] . '/library/functions.php');
include($_SERVER['DOCUMENT_ROOT'] . '/mvc/view/site/banner.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/mvc/view/site/columnist.php');

$result_config = DAOquery($sql = "SELECT * FROM config order by id desc limit 0,1;", [], true, "");
$elements_config = (isset($result_config) && !empty($result_config) && is_array($result_config) && isset($result_config["elements"]) && !empty($result_config["elements"]) && is_array($result_config["elements"])) ? $result_config["elements"] : null;
$GLOBALS["logo_site"] = "";
$GLOBALS["logo_site_mobile"] = "";
$GLOBALS["mensagem_contato"] = "Seu contato foi enviado com sucesso.";
if (is_array($elements_config)) {
    foreach ($elements_config as $config) {
        if (
            isset($config) &&
            !empty($config) &&
            is_array($config)
        ) {
            if (
                isset($config["logo"]) &&
                !empty($config["logo"])
            ) {
                $GLOBALS["logo_site"] = $config["logo"];
            }
            if (
                isset($config["mensagem_contato"]) &&
                !empty($config["mensagem_contato"])
            ) {
                $GLOBALS["mensagem_contato"] = $config["mensagem_contato"];
            }
            if (
                isset($config["logo_mobile"]) &&
                !empty($config["logo_mobile"])
            ) {
                $GLOBALS["logo_site_mobile"] = $config["logo_mobile"];
            }
        }
    }
}

function top()
{
    ?><!doctype html>
    <html lang="pt_BR">

    <head>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-LCB7JVDRSH"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-LCB7JVDRSH');
        </script>

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7414171345574828"
            crossorigin="anonymous"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <title><?php echo $GLOBALS["og_title"] . " - " . $GLOBALS["og_description"]; ?> </title>
        <link rel="icon" type="image/png" sizes="192x192"
            href="<?php echo $GLOBALS["base_url"]; ?>/uploads/logo/320x240/<?php echo $GLOBALS["logo_site_mobile"]; ?>">
        <meta name="description" content="<?php echo $GLOBALS["og_description"]; ?>">
        <meta property="og:image" content="<?php echo $GLOBALS["og_image"]; ?>" />
        <meta property="og:url" content="<?php echo $GLOBALS["og_url"]; ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="<?php echo $GLOBALS["og_title"] ?>" />
        <meta property="og:description" content="<?php echo $GLOBALS["og_description"]; ?>" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo $GLOBALS["base_url"]; ?>/assets/css/simpleLightbox.css">
        <link rel="stylesheet" href="<?php echo $GLOBALS["base_url"]; ?>/assets/css/tooeste.css">

        <link rel="stylesheet" href="<?php echo $GLOBALS["base_url"]; ?>/assets/css/fonts.css" type="text/css"
            charset="utf-8" />
        <link rel="stylesheet" href="<?php echo $GLOBALS["base_url"]; ?>/assets/fonts/IdealistSans/stylesheet.css"
            type="text/css" charset="utf-8" />
        <link rel="stylesheet" href="<?php echo $GLOBALS["base_url"]; ?>/assets/fonts/NunitoSans-Regular/stylesheet.css"
            type="text/css" charset="utf-8" />
        <meta name="theme-color" content="#fafafa">

    </head>

    <body style="overflow-x:hidden">


        <header class="bg-white">
            <button id="abrir_menus"
                class="mobile-show navbar-toggler collapsed rounded border border-dark color-primary position-absolute "
                style="right:5px;top:30px" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list"
                    viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                </svg>
            </button>
            <nav class="row py-0 mx-0" style="background-color:#F8F8FF">
                <div class="container">
                    <nav class=" d-flex justify-content-between align-items-center" style="background-color:#F8F8FF">
                        <div class="w-100 desktop-show d-none"></div>
                        <div>
                            <a href="<?php echo $GLOBALS["base_url"]; ?>" title="Portal Toledo Informação ao seu alcance"
                                class="desktop-show  d-none text-color-default pl-0 pr-2"
                                style="text-decoration:none;width:300px"><img alt="Portal Toledo Informação ao seu alcance"
                                    style="width:auto	;height:90px;"
                                    src="<?php echo $GLOBALS["base_url"]; ?>/uploads/logo/1366x768/<?php echo $GLOBALS["logo_site"]; ?>"
                                    title="Portal Toledo Informação ao seu alcance"></a>
                            <a href="<?php echo $GLOBALS["base_url"]; ?>" title="Portal Toledo Informação ao seu alcance"
                                class="mobile-show text-color-default pl-0 pr-2"
                                style="text-decoration:none;max-width:135px;width:135px"><img
                                    alt="Portal Toledo Informação ao seu alcance" style="width:135px;height:90px;"
                                    src="<?php echo $GLOBALS["base_url"]; ?>/uploads/logo_mobile/1366x768/<?php echo $GLOBALS["logo_site_mobile"]; ?>"
                                    title="Portal Toledo Informação ao seu alcance"></a>
                        </div>
                        <div class="d-flex justify-content-end w-100">
                            <button id="abrirsearchMobile"
                                class="mobile-show btn btn-outline-light mr-4 link_top my-sm-0 border-0 bg-transparent pr-4 text-primary"
                                type="submit">
                                <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-search abrirsearchMobile" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                </svg>
                            </button>
                            <div class="desktop-show align-items-center justify-content-end"
                                style="overflow: hidden;min-width:100px;">
                                <div class="d-flex">
                                    <?php include($_SERVER['DOCUMENT_ROOT'] . "/mvc/view/site/form_search.php"); ?>
                                </div>
                            </div>
                        </div>
                    </nav>
                    <nav id="searchMobile" class=" d-none bg-primary justify-content-between align-items-center ">
                        <div class="mobile-show w-100 justify-content-center d-none pr-4">
                            <?php include($_SERVER['DOCUMENT_ROOT'] . "/mvc/view/site/form_search_mobile.php"); ?>
                        </div>
                        <button id="fecharsearchMobile"
                            class="mobile-show position-absolute text-white border-0 bg-transparent" style="right:5px;"
                            type="button">
                            x
                        </button>
                    </nav>
                </div>
            </nav>
            <?php include($_SERVER['DOCUMENT_ROOT'] . "/mvc/view/site/menu_top.php"); ?>
        </header>


        <?php
        if (
            isset($GLOBALS['ler_menu']) &&
            (($GLOBALS['ler_menu'] != "Patrocinador") &&
                ($GLOBALS['ler_menu'] != "contato"))
        ) {
            ?>
            <hr class="w-100 p-2 m-0" style="background-color:#EAEDED">
            <div class="w-100 proportion-4x1 d-none desktop-show"><?php random_banner("banner abaixo do menu", 1); ?></div>
            <div class="w-100 proportion-3x4 d-none mobile-show"><?php random_banner_mobile("banner abaixo do menu", 1); ?>
            </div>
            <hr class="w-100 p-2 m-0" style="background-color:#EAEDED">
        <?php } ?>
        <div class="container">






    <?php
}

function foot()
{
    ?>

            <div class="row mt-3 block-row-1">

                <div class="col-sm-12 h-100">
                    <div class="w-100 h-100 "><?php columnist(); ?></div>
                </div>
            </div>


            <?php if (isset($GLOBALS['ler_menu']) && (($GLOBALS['ler_menu'] != "Patrocinador") && ($GLOBALS['ler_menu'] != "contato"))) { ?>
                <div class="row mt-3 block-row-1">

                    <div class="col-sm-12 h-100">
                        <div class="w-100 h-100 ">
                            <?php if (isset($GLOBALS['ler_menu']) && (($GLOBALS['ler_menu'] != "Patrocinador") && ($GLOBALS['ler_menu'] != "contato")))
                                banner_scroll("banner mini rotativo"); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>




        </div>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/mvc/view/site/menu_foot.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/mvc/view/site/term_use_foot.php"); ?>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
        <script src="<?php echo $GLOBALS["base_url"]; ?>/assets/js/simpleLightbox.js"></script>
        <script src="<?php echo $GLOBALS["base_url"]; ?>/assets/js/tooeste.js"></script>
        <script>
            window.ga = function () {
                ga.q.push(arguments)
            };
            ga.q = [];
            ga.l = +new Date;
            ga('create', 'UA-XXXXX-Y', 'auto');
            ga('set', 'anonymizeIp', true);
            ga('set', 'transport', 'beacon');
            ga('send', 'pageview')
        </script>
        <script src="https://www.google-analytics.com/analytics.js" async></script>

        <!-- Adsterra Scripts (Social Bar ou Popunder) -->
        <!-- Adsterra Scripts (Social Bar) -->
        <script type="text/javascript"
            src="//pl28233398.effectivegatecpm.com/33/1b/07/331b0762cca54662b381f710390c1c12.js"></script>
    </body>

    </html>
<?php } ?>