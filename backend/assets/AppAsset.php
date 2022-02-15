<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/jquery-ui.css',
        'css/custom.css',
        'css/jquery.dataTables.min.css',
        'css/font-awesome/css/fontawesome-all.css',
        'css/select2.min.css',
    ];
    public $js = [
        'js/jquery-ui.js',
        'js/import.js',
        'js/custom.js',
        'js/jquery.dataTables.js',
        'js/select2.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
	public $jsOptions = array(
		'position' => \yii\web\View::POS_HEAD
	);
}
