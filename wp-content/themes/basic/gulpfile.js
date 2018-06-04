const gulp          = require('gulp');
const sass          = require('gulp-sass');
const plumber       = require('gulp-plumber');
const autoprefixer  = require('gulp-autoprefixer');
const sourcemaps    = require('gulp-sourcemaps');
const browserSync   = require('browser-sync').create();
const gutil         = require('gulp-util');
const notify        = require("gulp-notify");

function showError(err) {
    notify.onError({
        title: "Gulp error in " + err.plugin,
        message:  err.message
    })(err);

    console.log(gutil.colors.red(err.toString()));
    this.emit('end');
}

gulp.task('serve', function() {
    browserSync.init({
        proxy: "http://localhost/Projekt",
        notify: false,
    });
});

gulp.task('sass', function () {
    return gulp.src('./scss/main.scss')
        .pipe(plumber({
            errorHandler : showError
        }))
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'compressed' //nested, expanded, compact, compressed
        }))
        .pipe(autoprefixer({
            browsers: ['last 2 versions']
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./css'))
        .pipe(browserSync.stream());
});

gulp.task('watch', function () {
    gulp.watch('./scss/**/*.scss', ['sass']);
    gulp.watch("./*.php").on('change', browserSync.reload);
});

gulp.task('default', function() {
    console.log(gutil.colors.green('======== Begin ========'));
    gulp.start(['sass', 'serve', 'watch']);
});
