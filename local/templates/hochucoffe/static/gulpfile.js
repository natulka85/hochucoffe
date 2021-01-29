'use strict';
var gulp =require('gulp');
var sass = require('gulp-sass');
//var runSequence = require('run-sequence');
var minifyCSS 	= require("gulp-clean-css");
var minifyJS 	= require("gulp-uglify-es").default,
    csso = require("gulp-csso"),
    shorthand = require('gulp-shorthand'),
    CSScomb = require('csscomb'),
    postcss = require('gulp-postcss'),
    babel = require('gulp-babel'),
    imagemin     = require('gulp-imagemin'),
    imgCompress  = require('imagemin-jpeg-recompress');
var csscomb = new CSScomb();
var autoprefixer = require('gulp-autoprefixer');
var iconfont = require('gulp-iconfont');
var iconfontCss = require('gulp-iconfont-css');
var sassUnicode = require('gulp-sass-unicode');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var ifElse = require('gulp-if-else');
var gulpif = require('gulp-if');
var extractMediaQuery = require('gulp-extract-media-queries');
var svgo = require('gulp-svgo');
const runSequence = require('gulp4-run-sequence');
var runTimestamp = Math.round(Date.now()/1000);
var pug = require('gulp-pug');
var plumber = require('gulp-plumber');
var sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync').create();
var reload      = browserSync.reload;
const through = require('through2');

function logBabelMetadata() {
    return through.obj((file, enc, cb) => {
        console.log(file.babel.test); // 'metadata'
        cb(null, file);
    });
}
/*
gulp.task('browserSync', function () {
    browserSync({
        server: {
            baseDir: '/dist',
        },
    });
});*/

var path = {
    //папка куда складываются готовые файлы
    build: {
        html: 'dist/html',
        js: 'dist/js/',
        css: 'dist/css/',
        img: 'dist/images/',
        fonts: 'dist/fonts/'
    },
    //папка откуда брать файлы
    src: {
        main: 'src',
        html: 'src/[^_]*.html',
        js: 'src/js/**/*.js',
        pug: 'src/pug/pages/*.pug',
        img: 'src/images/**/*.{jpg,jpeg,png,svg,gif}',
        fonts: 'src/fonts/**/*.*',
        style: 'src/scss/pages/*.scss',
        scss: 'src/scss',
        icons: 'src/icons/*.svg'
    },
    //указываем после измененя каких файлов нужно действовать
    watch: {
        html: 'src/**/*.html',
        pug: 'src/pug/**/*.pug',
        js: 'src/js/**/*.js',
        style: 'src/scss/**/*.{scss,css}',
        img: 'src/images/**/*.*',
        fonts: 'src/fonts/**/*.*',
    },
    clean: './dist'
};

gulp.task('pug:build', function buildHTML() {
    return gulp.src(path.src.pug)
        .pipe(plumber())
        .pipe(pug({
            doctype: 'html',
            pretty: true
        }))
        .pipe(gulp.dest(path.build.html))
        .pipe(reload({stream: true}));
});

gulp.task('sass:build', function(done) {
    gulp.src(path.src.style,{allowEmpty:true})
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass({includePaths: ['node_modules']}).on('error', sass.logError))
        .pipe(sassUnicode())
        .pipe(autoprefixer())
        .pipe(shorthand())
        /*.pipe(
            postcss([
                require('postcss-sorting')(
                    {
                        'order': [
                            'custom-properties',
                            'dollar-variables',
                            'declarations',
                            'at-rules',
                            'rules'
                        ],

                        'properties-order': 'alphabetical',

                        'unspecified-properties-position': 'bottom'
                    }
                )
            ]))*/
        .pipe(csso({
            restructure: false,
            sourceMap: true,
            debug: true
        }))
        //.pipe(minifyCSS()) //минификация css файла
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(path.build.css))
        .pipe(reload({stream: true}))
    done();
});

gulp.task('icons:build', async function(done) {
    return gulp.src(path.src.icons)
        .pipe(plumber())
        .pipe(iconfontCss({
            fontName: 'IconsFont',
            cssClass: 'icon',
            path: path.src.scss + '/icon-fonts/_icon-fonts-generate.scss',
            targetPath: '../../../src/scss/icon-fonts/_icon-fonts.scss',
            fontPath: '../fonts/IconsFont/'
        }))
        .pipe(iconfont({
            fontName: 'IconsFont', // required
            prependUnicode: true, // recommended option
            formats: ['ttf', 'eot', 'woff', 'woff2', 'svg'], // default, 'woff2' and 'svg' are available
            timestamp: runTimestamp, // recommended to get consistent builds when watching files
            normalize:true,
            fontHeight: 512,
            centerHorizontally:true,
        }))
        .on('glyphs', function(glyphs, options) {
            //console.log(glyphs['name']);
            //console.log(options);
            //console.log(/*glyphs, */glyphs.name);

            for (var from in glyphs) {
                console.log('from file - ', glyphs[from].name);
            }
        })
        .pipe(gulp.dest(path.build.fonts+'/IconsFont'))
});

function svgOptimize() {
    gulp.src('.../../images/svg/**/*.svg')
        .pipe(svgo())
        .pipe(gulp.dest('.../../images/minified-svg'));
}
gulp.task('js-libs', function() {
    return gulp.src(
        [   './node_modules/jquery/dist/jquery.js',
            './node_modules/swiper/swiper-bundle.js',
            './node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.js',
            //'./node_modules/barba.js/dist/barba.js',
            //'./node_modules/actual/actual.js',
            './src/js-vendors-concat/**/*.js'],
        {allowEmpty:true}
    )
        .pipe(plumber())
        .pipe(concat('vendors.js'))
        .pipe(gulp.dest(path.src.main+'/js/lib'));
});
gulp.task('style-libs', function(){
    return gulp.src([
        './node_modules/normalize.css/normalize.css',
        './node_modules/swiper/swiper-bundle.min.css',
        './node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.css'
    ])
        .pipe(plumber())
        .pipe(concat('_libs_node.scss'))
        .pipe(gulp.dest(path.src.scss+'/utils'))
        .pipe(browserSync.reload({stream: true}))
})
gulp.task('compress:JS', function (done) {
    gulp.src([path.src.js]) // path to your file
        .pipe(plumber())
        .pipe(babel({
            presets: ['@babel/env'],
            compact: false
        }))
        .pipe(minifyJS())
        .pipe(rename(function (path) {
            path.extname = '.min.js';
        }))
        .pipe(gulp.dest(path.build.js))
    //.pipe(logBabelMetadata());

    done();
});
gulp.task('compress:IMG', function() {
    return gulp.src(path.src.img)
        //.pipe(ifElse())
        .pipe(imagemin([
            imagemin.mozjpeg({quality: 92, progressive: true}),
            //imagemin.gifsicle({interlaced: true}),
            imagemin.optipng({optimizationLevel: 5}),
            imagemin.svgo({
                    plugins: [
                        {removeViewBox: true},
                        {cleanupIDs: false}
                    ]
                }
            )
        ]))
        .pipe(gulp.dest(path.build.img));
});

function watch(){
    gulp.watch('./scss/**/*.scss', sass);
    gulp.watch('./js/*.js',  gulp.series('compress:JS'));
    console.log('gulp-watch');
}
gulp.task('svg-task', function(){
    svgOptimize();
    gulp.watch([path.src.icons], svgOptimize);
});

gulp.task('watch', function() {
    gulp.watch(path.watch.style, gulp.series(['sass:build']));
    gulp.watch(path.src.icons, gulp.series('icons:build'));
    gulp.watch(path.watch.pug, gulp.series('pug:build'));
    gulp.watch(path.watch.js,  gulp.series('compress:JS'));
    gulp.watch('./src/js-vendors-concat/**/*.js',  gulp.series('js-libs'));
    gulp.watch(path.src.img,gulp.series('compress:IMG'));
    //gulp.watch(['./app/*.html','./app/css/**/*.css']).on('change', browserSync.reload);
});

gulp.task('fonts:copy',function(){
    return gulp.src([path.src.fonts]).pipe(gulp.dest(path.build.fonts));
})
gulp.task('img:copy',function(){
    return gulp.src([path.src.img]).pipe(gulp.dest(path.build.img));
})

gulp.task('default', gulp.series(
    //'svg-task', //ломаеет иконки нафиг
    //'pug:build',
    'sass:build',
    'icons:build',
    'fonts:copy',
    //'img:copy',
    'js-libs',
    'style-libs',
    'compress:JS',
    //'compress:IMG',
    'watch', function(done) {
        done();
    }));



