'use strict';

var gulp =require('gulp');
var sass = require('gulp-sass');
//var runSequence = require('run-sequence');
var minifyCSS 	= require("gulp-clean-css");
var minifyJS 	= require("gulp-uglify-es").default;
var autoprefixer = require('gulp-autoprefixer');
var iconfont = require('gulp-iconfont');
var iconfontCss = require('gulp-iconfont-css');
var sassUnicode = require('gulp-sass-unicode');
var rename = require('gulp-rename');
var ifElse = require('gulp-if-else');
    var gulpif = require('gulp-if');
var extractMediaQuery = require('gulp-extract-media-queries');
var svgo = require('gulp-svgo');
const runSequence = require('gulp4-run-sequence');

var runTimestamp = Math.round(Date.now()/1000);

var pug = require('gulp-pug');

gulp.task('pug:build', function buildHTML() {
    return gulp.src('./pug/**/*.pug')
        .pipe(pug({
            doctype: 'html',
            pretty: false
        }))
        .pipe(gulp.dest('./html/'));
});


var paths = {
    sass: {
        './scss/global.scss':'css', //1
        './scss/qr-page.scss':'css', //1
    }
};

gulp.task('sass:build', function(done) {
    for (var from in paths.sass) {
        var to = paths.sass[from];
        var components = from.indexOf('/components/') > 0;
        gulp.src(from,{allowEmpty:true})
            .pipe(sass(/*{outputStyle: 'compressed'}*/).on('error', sass.logError))
            .pipe(sassUnicode())
            .pipe(gulpif(components, rename({basename: 'style'})))
            //.pipe(extractMediaQuery())
            .pipe(autoprefixer({grid: true }))
            .pipe(minifyCSS())
            .pipe(gulp.dest(to));

        //console.log('modified from - ',from, 'to - ', paths.sass[from]);
    }

    done();
});

gulp.task('icons:build', function(done) {
    return gulp.src(['.../../images/icons/*.svg'])
        .pipe(iconfontCss({
            fontName: 'IconsFont',
            cssClass: 'icon',
            path: './scss/icon-fonts/_icon-fonts-generate.scss',
            targetPath: '../../scss/icon-fonts/_icon-fonts.scss',
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
        .pipe(gulp.dest('./fonts/IconsFont'))
});

function svgOptimize() {
    gulp.src('.../../images/svg/**/*.svg')
        .pipe(svgo())
        .pipe(gulp.dest('.../../images/minified-svg'));
}

gulp.task('compress:JS', function (done) {
    gulp.src('./js/*.js') // path to your file
        .pipe(minifyJS())
        .pipe(rename(function (path) {
            path.extname = '.min.js';
        }))
        .pipe(gulp.dest('./js/minify-js'));

    done();
});

function watch(){
    gulp.watch('./scss/**/*.scss', sass);
    gulp.watch('./js/*.js',  gulp.series('compress:JS'));
    console.log('gulp-watch');
}
gulp.task('svg-task', function(){
    svgOptimize();
    gulp.watch('.../../images/svg/**/*.svg', svgOptimize);
});

gulp.task('watch', function() {
    gulp.watch('./scss/**/*.scss', gulp.series('sass:build'));
    gulp.watch('.../../images/icons/!*.svg', gulp.series('icons:build'));
    gulp.watch('./pug/**/*.pug', gulp.series('pug:build'));
    gulp.watch('./js/*.js',  gulp.series('compress:JS'));
});

gulp.task('default', gulp.series(
    'sass:build',
    'icons:build',
    'pug:build',
    'compress:JS',
    'watch', function(done) {
    done();
}));



