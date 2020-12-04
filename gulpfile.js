const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass');
      sass.compiler = require('node-sass');

const babel = require('gulp-babel');
const sourcemaps = require('gulp-sourcemaps');
      
const purgecss = require('gulp-purgecss');
const autoprefixer = require('gulp-autoprefixer');
const svgSprite = require('gulp-svg-sprite');
const concat = require('gulp-concat');

//
// SVG Sprite Config
//
const config = {
  shape: {
    dimension: { // Set maximum dimensions
      maxWidth: 32,
      maxHeight: 32,
      precision: 2,
      attributes: false,
    },
    // spacing: { // Add padding
    //   padding: 10,
    //   box: 'padding'
    // },
  },
  mode: {
    symbol: {
      dest: './',
      sprite: 'sprite.svg'
    }
  },
  dest: './'
};

//
// Tasks
//
//
function css(){
  return src('./application/views/scss/main-ltr.scss', './application/views/scss/main-rtl.scss')
      .pipe(sourcemaps.init({loadMaps: true}))
			.pipe(sass())
      .pipe(autoprefixer())
      // .pipe(purgecss({
      //   content: ['./application/views/**/*.php', './application/views/**/*.js']
      // }))
      .pipe(sourcemaps.write('.'))
			.pipe(dest('./application/views/css'));
}

// function js(){
  // return src('./resources/js/*.js')
  //     .pipe(sourcemaps.init({loadMaps: true}))
	// 		.pipe(babel({
  //       presets: ['@babel/env']
  //     }))
  //     .pipe(concat('main.js'))
  //     .pipe(sourcemaps.write('.'))
	// 		.pipe(dest('./js'));
// }

// function svg(){
//   return src('./resources/images/sprite/*.svg')
//       .pipe(svgSprite(config))
//       .pipe(dest('./images'));
// }

// Watch files
function watchFiles() {
	// Watch SCSS changes
  watch(['./application/views/scss'], css);
}

exports.default = css;
exports.watch = watchFiles;
