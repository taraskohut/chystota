/*
* run "npm install" to install all dependencies from package.json or run those manually:
npm install gulp gulp-sass sass node-sass gulp-rename gulp-concat gulp-uglify-es gulp-sourcemaps gulp-autoprefixer gulp-notify --save
*
*/

'use strict';

var gulp = require('gulp'),
    sass = require('gulp-sass')(require('sass')),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify-es').default,
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer'),
    notify = require('gulp-notify'),
    rename = require('gulp-rename');

var notifyOptions = {
    message: 'Error:: <%= error.message %> \nLine:: <%= error.line %> \nCode:: <%= error.extract %>'
};

gulp.task('styles', function() {
    return gulp
        .src('./assets/sass/style.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({ outputStyle: 'compressed' }).on('error', notify.onError(notifyOptions)))
        .pipe(autoprefixer())
        .pipe(concat('style.min.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./dist/css/'));
});
 
gulp.task('scripts', function() {
    return gulp
        .src('./assets/js/*.js')
        .pipe(sourcemaps.init())
        .pipe(rename({ extname: '.min.js' }))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./dist/js/'));
});


/* run task for continuous track of theme scss files */
gulp.task('watch', function() {
    gulp.watch('./assets/sass/**/*.scss', gulp.series('styles'));
    gulp.watch('./assets/js/**/*.js', gulp.series('scripts'));
});

gulp.task('compile', gulp.series('styles','scripts'));