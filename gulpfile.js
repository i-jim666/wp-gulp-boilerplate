// Defining requirements
var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var babel = require('gulp-babel');
var postcss = require('gulp-postcss');
var watch = require('gulp-watch');
var touch = require('gulp-touch-fd');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin');
var ignore = require('gulp-ignore');
var rimraf = require('gulp-rimraf');
var sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync').create();
var del = require('del');
var cleanCSS = require('gulp-clean-css');
var replace = require('gulp-replace');
var autoprefixer = require('autoprefixer');

// Configuration file to keep your code DRY
var cfg = require('./gulpconfig.json');
var paths = cfg.paths;


// Run:
// gulp sass
// Compiles SCSS files in CSS
gulp.task('sass', function () {
	var stream = gulp
		.src(paths.sass + '/*.scss')
		.pipe(
			plumber({
				errorHandler: function (err) {
					console.log(err);
					this.emit('end');
				}
			})
		)
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(sass({ errLogToConsole: true }))
		.pipe(postcss([autoprefixer()]))
		.pipe(sourcemaps.write(undefined, { sourceRoot: null }))
		.pipe(gulp.dest(paths.distcss))
		.pipe(touch());
	return stream;
});


// Run:
// gulp watch
// Starts watcher. Watcher runs gulp sass task on changes
gulp.task('watch', function () {
	gulp.watch([`${paths.sass}/**/*.scss`, `${paths.sass}/*.scss`], gulp.series('styles'));
	gulp.watch(
		[
			`${paths.dev}/js/**/*.js`,
			'js/**/*.js',
			'!js/theme.js',
			'!js/theme.min.js'
		],
		gulp.series('scripts')
	);
});



gulp.task('minifycss', function () {

	return gulp
		.src([
			`${paths.distcss}/theme.css`,
		])
		.pipe(sourcemaps.init({
			loadMaps: true
		}))
		.pipe(cleanCSS({
			compatibility: '*'
		}))
		.pipe(
			plumber({
				errorHandler: function (err) {
					console.log(err);
					this.emit('end');
				}
			})
		)
		.pipe(rename({ suffix: '.min' }))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(paths.distcss))
		.pipe(touch());
});



gulp.task('cleancss', function () {
	return gulp
		.src(`${paths.css}/*.min.css`, { read: false }) // Much faster
		.pipe(ignore('theme.css'))
		.pipe(rimraf());
});

gulp.task('styles', function (callback) {
	gulp.series('sass', 'minifycss')(callback);
});

// Run:
// gulp browser-sync
// Starts browser-sync task for starting the server.
gulp.task('browser-sync', function () {
	browserSync.init(cfg.browserSyncWatchFiles, cfg.browserSyncOptions);
});

// Run:
// gulp scripts.
// Uglifies and concat all JS files into one
gulp.task('scripts', function () {
	var scripts = [
		`${paths.dev}/js/*.js`
	];
	gulp
		.src(scripts, { allowEmpty: true })
		.pipe(babel(
			{
			presets: ['@babel/preset-env']
			}
		))
		.pipe(concat('theme.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest(paths.distjs));

	return gulp
		.src(scripts, { allowEmpty: true })
		.pipe(babel())
		.pipe(concat('theme.js'))
		.pipe(gulp.dest(paths.distjs));
});

// Deleting any file inside the /src folder
gulp.task('clean-source', function () {
	return del(['src/**/*']);
});

// Run:
// gulp watch-bs
// Starts watcher with browser-sync. Browser-sync reloads page automatically on your browser
gulp.task('watch-bs', gulp.parallel('browser-sync', 'watch'));

// Run
// gulp compile
// Compiles the styles and scripts and runs the dist task
gulp.task('compile', gulp.series('styles', 'scripts'));

// Run:
// gulp
// Starts watcher (default task)
gulp.task('default', gulp.series('watch'));