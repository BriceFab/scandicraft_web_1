const Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    /*
     * ENTRIES CONFIG
     *
     */
    .addEntry('styles', './assets/styles/index.js')                     //style scss
    .addEntry('mc_skins', './assets/styles/mc_skins/index.js')                     //mc skin/cape/head less
    .addEntry('app', './assets/javascript/app/index.js')                //javascript principal
    .addEntry('easy_admin', './assets/javascript/admin/main.js')        //js admin
    .addEntry('editor_app', './assets/javascript/js_editor/index.js')   //js editeur
    .addEntry('notifications', './assets/javascript/notifications/index.js')   //client notification
    .addEntry('html_elements', './assets/javascript/_common/html_elements/index.js')   //client custom html elements

    //Twigs JS scripts
    .addEntry('upload_skin', './assets/javascript/twig/upload_skin.js')

    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     *
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .configureBabel(function (babelConfig) {
        babelConfig.plugins.push("@babel/plugin-proposal-class-properties");
        babelConfig.presets.push('@babel/preset-flow');
    })

    .enableSassLoader()
    .enableLessLoader()
    // .configureCssLoader((options) => {
    //     console.log('options', options)
    //     options.importLoaders = 1;
    //     options.modules = {
    //         localIdentName: '[path][name]__[local]--[hash:base64:5]'
    //     };
    // })
    .enableIntegrityHashes(Encore.isProduction())
    .autoProvidejQuery()
    .enableReactPreset()

    //ckeditor
    .copyFiles([
        {
            from: './node_modules/ckeditor/',
            to: 'ckeditor/[path][name].[ext]',
            pattern: /\.(js|css)$/,
            includeSubdirectories: false
        },
        {from: './node_modules/ckeditor/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
        {from: './node_modules/ckeditor/lang', to: 'ckeditor/lang/[path][name].[ext]'},
        {from: './node_modules/ckeditor/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
        {from: './node_modules/ckeditor/skins', to: 'ckeditor/skins/[path][name].[ext]'}
    ])

    .addPlugin(new webpack.BannerPlugin('© CopyRight ScandiCraft. Développé par BriceFab (https://www.scandicraft-mc.fr)'))

module.exports = Encore.getWebpackConfig();