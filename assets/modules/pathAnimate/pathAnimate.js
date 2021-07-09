const pathAnimate = el => {
    var inc = 0;
    el.querySelectorAll("path").forEach((line, i) => {
        const length = line.getTotalLength();
        line.style.strokeWidth = 3;
        line.style.animationName = "pathAnimate";
        line.style.animationFillMode = "forwards";
        line.style.animationTimingFunction = "linear";
        line.style.strokeDasharray = length + " " + length;
        line.style.strokeDashoffset = length;
        line.style.animationDelay = 0 + "s";
        line.style.animationDuration = length / 1000 + "s";
        inc = inc + length / 1000;
    });
}

export default pathAnimate;