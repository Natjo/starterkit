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

const theme = 'starterkit';
const src = 'assets/';
const dist = `web/wp-content/themes/${theme}/`;
const styles = ['reset.css', 'variables.css', 'fonts.css', 'styles.css', 'layout.css',  'header_nav.css', 'footer.css'];


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
    styles: [],
    initTime: new Date(),
    compile(file, dist_name, ext){
        if(ext == '.js') this.babel(fs.readFileSync(file, 'utf8'), dist_name);
        else if(ext == '.css') this.postcss(fs.readFileSync(file, 'utf8'), dist_name);
        else fs.copySync(file, dist_name);
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
    postcss(result, dest){
        postcss([cssnested, 
        cssCustomMedia({importFrom: `${src}styles/customMedias.css`}), 
        autoprefixer({add: true})])
        .process(result, {from: 'undefined'})
        .then(result => {
            const minify = isProd ? uglifycss.processString(result.css) : result.css;   
            fs.ensureDirSync(path.dirname(dest));
            fs.writeFileSync(dest, minify);
        })
    },
    console(folder, filename, evt){
        let status;
        if(evt == 'remove') status = `31mremoved`;
        if(evt == 'update') status = `32mupdated`;
        if(evt == 'add') status = `36madded`;
        console.log(`\x1b[90m\x1b[3m(${folder})\x1b[39m\x1b[23m`, `\x1b[1m${filename}\x1b[22m`, `\x1b[${status}\x1b[39m`, `\x1b[3m${core.time()}s\x1b[23m`);
    }, 
    compile_syles(){
        let str;
        for(let file of styles){
            str += fs.readFileSync(`${src}styles/${file}`, 'utf8');
        }
        core.postcss(str, `${dist}assets/styles.css`);
    }
}

core.rmDir(`${dist}${src}`);
core.dirScan(src);
core.compile_syles();
json.create();

console.log(`${core.time()}s`);  

if(isProd) return
watch(src, {recursive: true}, (evt, file) => {
    if(/.DS_Store$/.test(file)) return 

    core.initTime = new Date();
    const isFile = file.indexOf(".") > 0 ? true : false;
    const filename = path.basename(file);
    const ext = path.extname(filename);
    const dist_file = dist + file;
    const folder = file.split('/')[1]; // module, view, styles, img, fonts ..
    const key = filename.replace(ext, '') // stage, block-intro ...
    const view = file.split('/')[2]; // footer, header, strate-intro ..
    
    if(!fs.existsSync(dist_file)) evt = 'add';

    if(evt == 'update' || evt == 'add') core.compile(file, dist_file, ext);
    if(folder === 'styles') core.compile_syles();

    if(folder == 'views' && ext != '.php'){
        if(isFile) json.datas[key][ext.replace('.','')] = ''; 
        else delete json.datas[key];
        evt != 'remove' && json.add(file, filename, ext);
        json.create(json.datas);
    }
    isFile && evt == 'remove' ? fs.unlinkSync(dist_file) : core.rmDir(dist_file);
    core.console(`${folder}-${view}`,filename,evt);
});

console.log(`I'm Watching you...`);