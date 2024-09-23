const mix = require("laravel-mix");
const glob = require("glob");

mix.sass("resources/sass/app.scss", "public/css");
glob.sync("resources/sass/pages/**/*.scss").forEach((file) => {
  const relativePath = file.replace("resources/sass/", "");
  const outputPath = `public/css/${relativePath.replace(".scss", ".css")}`;

  mix.sass(file, outputPath);
});

mix
  .js("resources/js/app.js", "public/js")
  .version()
  .browserSync({
    proxy: "127.0.0.1:8000",
    files: [
      "app/**/*.php",
      "resources/views/**/*.blade.php",
      "public/js/**/*.js",
      "public/css/**/*.css",
    ],
    open: false,
    notify: false,
  });
