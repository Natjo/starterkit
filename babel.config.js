module.exports = {
    presets: [
        [
            "@babel/preset-env",
            {
                useBuiltIns: "entry",
                corejs: "3.23.1",
                modules: false,
            },
        ],
    ],
};
