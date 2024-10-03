"use strict";

var KanjivgAnimate = (function () {
    var animators = [];
    function KanjivgAnimate(trigger, time) {
        time = typeof time !== "undefined" ? time : 500;
        this.setOnClick(trigger, time);
    }

    function stopAllAnimators() {
        for (var i = 0; i < animators.length; i++) {
            animators[i].stop();
        }
        animators = [];
    }

    KanjivgAnimate.prototype.setOnClick = function setOnClick(trigger, time) {
        var triggers = document.querySelectorAll(trigger);
        var length = triggers.length;

        for (var i = 0; i < length; i++) {
            triggers[i].onclick = function () {
                stopAllAnimators();

                var animate = new KVGAnimator(time);
                animators.push(animate);

                animate.play(this);
                return false;
            };
        }
    };

    return KanjivgAnimate;
})();

var KVGAnimator = (function () {
    function KVGAnimator(time) {
        this.time = time;
        this.isAnimating = false;
        this.count = 0;
        this.animationFrameId = null;
    }

    KVGAnimator.prototype.play = function play(trigger) {
        this.isAnimating = true;
        var svg = this.findTarget(trigger);

        if (!svg || svg.tagName !== "svg" || svg.querySelectorAll("path").length <= 0) {
            this.isAnimating = false;
            return;
        }

        this.paths = svg.querySelectorAll("path");
        this.numbers = svg.querySelectorAll("text");
        this.pathCount = this.paths.length;

        this.hideAll();

        this.count = 0;

        var path = this.paths[this.count];
        var number = this.numbers[this.count];

        this.animatePath(path, number);
    };

    KVGAnimator.prototype.stop = function () {
        this.isAnimating = false;
        if (this.animationFrameId) {
            cancelAnimationFrame(this.animationFrameId);
            this.animationFrameId = null;
        }
    };

    KVGAnimator.prototype.findTarget = function findTarget(trigger) {
        var attribute = "data-kanjivg-target";

        if (!trigger.hasAttribute(attribute)) {
            return trigger;
        }

        var target = trigger.getAttribute(attribute);
        return document.querySelector(target);
    };

    KVGAnimator.prototype.hideAll = function hideAll() {
        for (var i = 0; i < this.pathCount; i++) {
            this.paths[i].style.display = "none";
            if (typeof this.numbers[i] !== "undefined") {
                this.numbers[i].style.display = "none";
            }
        }
    };

    KVGAnimator.prototype.animatePath = function animatePath(path, number) {
        this.length = path.getTotalLength();

        path.style.display = "block";

        if (typeof number !== "undefined") {
            number.style.display = "block";
        }

        path.style.transition = path.style.WebkitTransition = "none";
        path.style.strokeDasharray = this.length + " " + this.length;
        path.style.strokeDashoffset = this.length;

        path.getBoundingClientRect();

        this.doAnimation(path);
    };

    KVGAnimator.prototype.doAnimation = function doAnimation(path) {
        if (!this.isAnimating) return;

        this.length--;

        path.style.strokeDashoffset = this.length;

        if (this.length >= 0) {
            this.animationFrameId = requestAnimationFrame(this.doAnimation.bind(this, path));
        } else {
            this.count += 1;

            if (this.count < this.pathCount) {
                var newPath = this.paths[this.count];
                var newNumber = this.numbers[this.count];
                this.animatePath(newPath, newNumber);
            }
        }
    };

    return KVGAnimator;
})();

window.KanjivgAnimate = KanjivgAnimate;
