WebP Express 0.20.1. Conversion triggered using bulk conversion, 2021-07-23 10:23:49

*WebP Convert 2.6.0*  ignited.
- PHP version: 7.2.34
- Server software: nginx/1.21.1

Stack converter ignited

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/uploads/2021/07/interview666--542x434.jpg
- destination: [doc-root]/wp-content/uploads/2021/07/interview666--542x434.jpg.webp
- log-call-arguments: true
- converters: (array of 10 items)

The following options have not been explicitly set, so using the following defaults:
- auto-limit: true
- converter-options: (empty array)
- shuffle: false
- preferred-converters: (empty array)
- extra-converters: (empty array)

The following options were supplied and are passed on to the converters in the stack:
- default-quality: 70
- encoding: "lossy"
- max-quality: 80
- metadata: "none"
- quality: "auto"
------------


*Trying: imagick* 

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/uploads/2021/07/interview666--542x434.jpg
- destination: [doc-root]/wp-content/uploads/2021/07/interview666--542x434.jpg.webp
- default-quality: 70
- encoding: "lossy"
- log-call-arguments: true
- max-quality: 80
- metadata: "none"
- quality: "auto"

The following options have not been explicitly set, so using the following defaults:
- alpha-quality: 85
- auto-limit: true
- auto-filter: false
- low-memory: false
- method: 6
- preset: "none"
- sharp-yuv: true
- skip: false
------------

*Setting "quality" to "auto" is deprecated. Instead, set "quality" to a number (0-100) and "auto-limit" to true. 
*"quality" has been set to: 80 (took the value of "max-quality").*
*"auto-limit" has been set to: true."*
Running auto-limit
Quality setting: 80. 
Quality of jpeg: 82. 
Auto-limit result: 80 (no limiting needed this time).
imagick succeeded :)

Converted image in 117 ms, reducing file size with 61% (went from 44 kb to 17 kb)
