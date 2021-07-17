const fs = require('fs-extra');
const path = require('path');
const postcss = require('postcss');
const cssnested = require('postcss-nested');
const cssCustomMedia = require('postcss-custom-media');
const autoprefixer = require('autoprefixer');
const uglifycss = require('uglifycss');
const babel = require('@babel/core');
const watch = require('node-watch');
const isProd = process.argv[2] == '--prod' ? true : false;
require('dotenv').config({ path: '.docker/.env' })

const src = 'assets/';
const dist = `web/wp-content/themes/${process.env.THEME_NAME}/`;

const json = {
    datas: {},
    add(name, filename, ext){
        const dir = filename.replace(ext, '')
        if(!this.datas[dir] && ext != '.php') this.datas[dir] = {
            js: '',
            css: ''
        };
        if(ext == '.js') this.datas[dir].js = name;
        if(ext == '.css') this.datas[dir].css = name; 
    },
    create: () => fs.writeFileSync(`${dist}${src}views.json`, JSON.stringify(json.datas))
}

const core = {
    initTime: new Date(),
    compile(file, dist_name, ext){
        if(ext == '.js') this.babel(fs.readFileSync(file, 'utf8'), dist_name);
        else if(ext == '.css'){
            this.postcss(file, css => {
               // console.log(css);
                fs.ensureDirSync(path.dirname(dist_name));
                fs.writeFileSync(dist_name, css); 
            });
        }
        else fs.copySync(file, dist_name);
    },
    compile_syles() {
        
        const dir = `${src}styles/`;
        let str = '';
        styles = [];
        
        fs.readdirSync(dir).forEach(res => {
            const file = path.resolve(dir, res);
            if (/.css$/.test(file)) {
                if(!/customMedias.css$/.test(file)){
                    styles.push(file);
                } 
            }
        });
        styles.forEach((file, inc) => {
           core.postcss(file, css => { 
                str += css;
                if(inc == styles.length - 1) {
                    fs.ensureDirSync(path.dirname(`${dist}assets/styles.css`));
                    fs.writeFileSync(`${dist}assets/styles.css`, str); 
                }      
            });
        });
	},
    dirScan(dir) {
        const recursive = dir => {
            fs.readdirSync(dir).forEach(res => {
                const file = path.resolve(dir, res);
                const stat = fs.statSync(file);
                if (stat && stat.isDirectory()) recursive(file);
                else if (!/.DS_Store$/.test(file)) { 
                    if(!/\/styles\//.test(file)){
                        const name = file.replace(`${__dirname}/`, '');
                        const filename = path.parse(name).base;
                        const ext = path.extname(filename);
                        if(/\/views\//.test(file)) json.add(name, filename, ext);
                        core.compile(file, dist + name, ext); 
                    }
                }
            });
        }
        recursive(dir);
	},
    rmDir(dirPath, removeSelf) {
        if(removeSelf === undefined) removeSelf = true;
        try{ var files = fs.readdirSync(dirPath); }
        catch(e){ return; }
        for(let file of files){
            const filePath = `${dirPath}/${file}`;
            fs.statSync(filePath).isFile() ? fs.unlinkSync(filePath) : core.rmDir(filePath);
        }
        removeSelf && fs.rmdirSync(dirPath);
	},
    time : () => time = (new Date() - core.initTime) / 1000,
    babel(result, dest){
        result = babel.transform(result, {
            minified: isProd ? true : false,
            comments: false,
            presets: isProd ? [["minify", {"builtIns": 'entry'}]] : [],

        }).code;

        fs.ensureDirSync(path.dirname(dest));
        fs.writeFileSync(dest, result);
    },
    postcss(file, func){
        const str = fs.readFileSync(file, 'utf8');
        postcss([cssnested, 
        cssCustomMedia({importFrom: `${src}styles/customMedias.css`}), 
        autoprefixer({add: true})])
        .process(str, {from: file})
        .catch(error => {
            console.log('');
            console.log(`\x1b[31mError CSS`);
            console.log(`\x1b[90m${error.file}\x1b[39m\x1b[23m`);
            console.log(error.reason, 'line:',error.line,'col',error.column);
            console.log('');
        })
        .then(result => {
            func(isProd ? uglifycss.processString(result.css) : result.css);
        })
    },
    console(folder, filename, evt){
        let status;
        if(evt == 'remove') status = `31mremoved`;
        if(evt == 'update') status = `32mupdated`;
        if(evt == 'add') status = `36madded`;
        console.log(`\x1b[90m\x1b[3m(${folder})\x1b[39m\x1b[23m`, `\x1b[1m${filename}\x1b[22m`, `\x1b[${status}\x1b[39m`, `\x1b[3m${core.time()}s\x1b[23m`);
    }
}

core.rmDir(`${dist}${src}`);
core.dirScan(src);
json.create();
core.compile_syles();

console.log(`${core.time()}s`);  

if(isProd) return

watch(src, {recursive: true}, (evt, file) => {
    if(/.DS_Store$/.test(file)) return
    core.initTime = new Date();
    const isFile = file.indexOf('.') > 0 ? true : false;
    const filename = path.basename(file);
    const ext = path.extname(filename);
    const dist_file = dist + file;
    const folder = file.split('/')[1]; // module, view, styles, img, fonts ..
    const key = filename.replace(ext, '') // stage, block-intro ...
    const view = file.split('/')[2]; // footer, header, strate-intro ..
    const exist = fs.existsSync(dist_file) ? true : false;

    if(!fs.existsSync(dist_file)) evt = 'add';

    if(evt == 'update' || evt == 'add') core.compile(file, dist_file, ext);
   
    if(folder == 'views' && ext != '.php'){
        if(isFile) json.datas[key][ext.replace('.','')] = ''; 
        else delete json.datas[key];
        evt != 'remove' && json.add(file, filename, ext);
        json.create(json.datas);
    }

    if (exist && evt == 'remove') {
        isFile ? fs.unlinkSync(dist_file) : core.rmDir(dist_file);
    }
    
    if(folder === 'styles'){
        core.compile_syles();
        core.console(folder,filename,evt);
    } else{
        core.console(`${folder}-${view}`,filename,evt);
    }
});

console.log(`I'm Watching you...`);