const fs = require('fs-extra');
const path = require('path');
const postcss = require('postcss');
const cssnested = require('postcss-nested');
const cssCustomMedia = require('postcss-custom-media');
const postcssGlobalData = require('@csstools/postcss-global-data');
const autoprefixer = require('autoprefixer');
const uglifycss = require('uglifycss');
const babel = require('@babel/core');
const watch = require('node-watch');
const isProd = process.argv[2] == '--prod' ? true : false;
require('dotenv').config({ path: '.docker/.env' })

const { optimize } = require('svgo');
const breakpoints = [576, 768, 992, 1200, 1440];
const dispatch = { mobile: "", desktop: "" };

const src = 'assets/';
const dist = `web/wp-content/themes/${process.env.THEME_NAME}/`;
let commonstyles = [];

const json = {
    datas: {},
    add(name, filename, ext) {
        const dir = filename.replace(ext, '')
        if (!this.datas[dir] && ext != '.php') this.datas[dir] = {
            js: '',
            css: ''
        };
        if (ext == '.js') this.datas[dir].js = name;
        if (ext == '.css') this.datas[dir].css = name;
    },
    create: () => fs.writeFileSync(`${dist}${src}views.json`, JSON.stringify(json.datas))
}

const core = {
    initTime: new Date(),
    compile(file, dist_name, ext) {

        if (ext == '.js') this.babel(fs.readFileSync(file, 'utf8'), dist_name);
        else if (ext == '.css') {
            this.postcss(file, css => {
                fs.ensureDirSync(path.dirname(dist_name));
                fs.writeFileSync(dist_name, css);
            });
        }
        else if (ext == '.svg') {
            if (isProd) {
                const svgString = fs.readFileSync(file, 'utf8');
                const result = optimize(svgString, {
                    path: dist_name,
                    multipass: true,
                    plugins: ["removeUselessDefs"]
                });
                const optimizedSvgString = result.data;
                fs.ensureDirSync(path.dirname(dist_name));
                fs.writeFileSync(dist_name, optimizedSvgString);
            } else {
                fs.copySync(file, dist_name);
            }
        }
        else fs.copySync(file, dist_name);
    },
    dispatchCSS(str) {
        const regexp = /@media[ ]{0,}\(([min|max]+-width):[ ]{0,}([0-9]+)px\)[ ]{0,}{([\s\S]+?})\s*}/g;
        let results = str.matchAll(regexp);

        for (const result of results) {
            const up = result[1] == 'max-width' ? false : true;
            const breakpoint = Number(result[2]);
            const content = result[0];

            if (!up) {
                if ((breakpoint < 992)) {
                    dispatch.mobile += content;
                } else {
                    dispatch.desktop += content;
                }
            } else {
                if ((breakpoint >= 992)) {
                    dispatch.desktop += content;
                } else {
                    dispatch.mobile += content;
                }
            }
            str = str.replace(content, "");
        }

        fs.writeFileSync(`${dist}assets/styles-mobile.css`, str + dispatch.mobile);
        fs.writeFileSync(`${dist}assets/styles-desktop.css`, str + dispatch.desktop);
    },
    compile_syles() {
        let str = '';
        let inc = 0;
        for (let file of commonstyles) {
            if (/.css$/.test(file)) {
                core.postcss(file, css => {
                    str += css;
                    inc++;
                    if (inc == commonstyles.length) {
                        //core.dispatch(str);
                        fs.writeFileSync(`${dist}assets/styles.css`, str);
                    }
                });
            }
        }
    },
    dirScan(dir) {
        const recursive = dir => {
            fs.readdirSync(dir).forEach(res => {
                const file = path.resolve(dir, res);
                const stat = fs.statSync(file);
                if (stat && stat.isDirectory()) recursive(file);
                else if (!/.DS_Store$/.test(file)) {
                    if (/\/styles\//.test(file)) {
                        if (!/customMedias.css$/.test(file)) {
                            commonstyles.push(file);
                        }
                    } else {
                        const name = file.replace(`${__dirname}/`, '');
                        const filename = path.parse(name).base;
                        const ext = path.extname(filename);
                        if (/\/views\//.test(file)) json.add(name, filename, ext);
                        core.compile(file, dist + name, ext);
                    }
                }
            });
        }
        recursive(dir);
    },
    rmDir(dirPath, removeSelf) {
        if (removeSelf === undefined) removeSelf = true;
        try { var files = fs.readdirSync(dirPath); }
        catch (e) { return; }
        for (let file of files) {
            const filePath = `${dirPath}/${file}`;
            fs.statSync(filePath).isFile() ? fs.unlinkSync(filePath) : core.rmDir(filePath);
        }
        removeSelf && fs.rmdirSync(dirPath);
    },
    time: () => time = (new Date() - core.initTime) / 1000,
    babel(result, dest) {
        result = babel.transform(result, {
            minified: isProd ? true : false,
            comments: false,
            presets: isProd ? [["minify", { "builtIns": 'entry' }]] : []
        }).code;

        fs.ensureDirSync(path.dirname(dest));
        fs.writeFileSync(dest, result);
    },
    postcss(file, func) {
        const str = fs.readFileSync(file, 'utf8');
        postcss([cssnested,
            postcssGlobalData({
                files: [`${src}styles/customMedias.css`]
            }),
            cssCustomMedia(),
            autoprefixer({ add: true })])
            .process(str, { from: file })
            .catch(error => {
                console.log('');
                console.log(`\x1b[31mError CSS`);
                console.log(`\x1b[90m${error.file}\x1b[39m\x1b[23m`);
                console.log(error.reason, 'line:', error.line, 'col', error.column);
                console.log('');
            })
            .then(result => {
                //console.log(result.css);
                //var tteesstt = 991;
                // var b= '@media[ ]{0,}\(max-width:[ ]{0,}991px\)[ ]{0,}{([\s\S]+?})\s*}';
                // const rere =  RegExp(b, 'gi');
                //const test = `@media[ ]{0,}\(max-width:[ ]{0,}991px\)[ ]{0,}{([\s\S]+?})\s*}`;
                //const regexp = `\\b/@media[ ]{0,}\(max-width:[ ]{0,}991px\)[ ]{0,}{([\s\S]+?})\s*}/g\\b`;

                func(isProd ? uglifycss.processString(result.css) : result.css);
            })
    },
    console(folder, filename, evt) {
        let status;
        if (evt == 'remove') status = `31mremoved`;
        if (evt == 'update') status = `32mupdated`;
        if (evt == 'add') status = `36madded`;
        console.log(`\x1b[90m\x1b[3m(${folder})\x1b[39m\x1b[23m`, `\x1b[1m${filename}\x1b[22m`, `\x1b[${status}\x1b[39m`, `\x1b[3m${core.time()}s\x1b[23m`);
    }
}

core.rmDir(`${dist}${src}`);
core.dirScan(src);
json.create();
core.compile_syles();

console.log(`${core.time()}s`);

if (isProd) return

watch(src, { recursive: true }, (evt, file) => {
    if (/.DS_Store$/.test(file)) return
    core.initTime = new Date();
    const isFile = file.indexOf('.') > 0 ? true : false;
    const filename = path.basename(file);
    const ext = path.extname(filename);
    const dist_file = dist + file;
    const folder = file.split('/')[1]; // module, view, styles, img, fonts ..
    const key = filename.replace(ext, '') // stage, block-intro ...
    const view = file.split('/')[2]; // footer, header, strate-intro ..
    const exist = fs.existsSync(dist_file) ? true : false;

    if (folder !== 'styles') {
        if (!fs.existsSync(dist_file)) evt = 'add';
        if (evt == 'update' || evt == 'add') core.compile(file, dist_file, ext);
    }

    if (folder == 'views' && ext != '.php') {
        if (isFile) json.datas[key][ext.replace('.', '')] = '';
        else delete json.datas[key];
        evt != 'remove' && json.add(file, filename, ext);
        json.create(json.datas);
    }

    if (exist && evt == 'remove') {
        isFile ? fs.unlinkSync(dist_file) : core.rmDir(dist_file);
    }

    if (folder === 'styles') {
        core.compile_syles();
        core.console(folder, filename, evt);
    } else {
        core.console(`${folder}${view ? `-${view}` : ''}`, filename, evt);
    }
});



console.log(`I'm Watching you...`);