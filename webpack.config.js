const Encore = require('@symfony/webpack-encore');
//DECLARATION OF THE NEW PLUGIN
const CopyWebpackPlugin = require('copy-webpack-plugin');

const config = {
    module: {
        loaders: [
            {
                test: require.resolve('tinymce/tinymce'),
                loaders: [
                    'imports?this=>window',
                    'exports?window.tinymce'
                ]
            },
            {
                test: /tinymce\/(themes|plugins)\//,
                loaders: [
                    'imports?this=>window'
                ]
            }
        ]
    }
}

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .addEntry('styles/tiny-mce', './assets/styles/tiny-mce.css')
    .addEntry('scripts/tiny-mce', './assets/scripts/tiny-mce.js')
    .addEntry('scripts/template_script', './assets/scripts/template_script.js')
    .addEntry('scripts/navbar', './assets/scripts/navbar.js')
    .addEntry('scripts/inbox', './assets/scripts/inbox.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
    .enablePostCssLoader()
    .addPlugin(new CopyWebpackPlugin({
        patterns: [
            // Copy the skins from tinymce to the build/skins directory
            { from: 'node_modules/tinymce/skins', to: 'skins' },
        ],
    }))

;

module.exports = Encore.getWebpackConfig();