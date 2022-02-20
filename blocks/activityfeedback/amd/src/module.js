//Creates feedback options on each activity
//and handles it by AJAX calls to the webservices.
//----------------------------------------------------------------------------------------------------------------------------------
//Must always be compiled by Babel JS with Grunt, see ..\build\module.min.js
//see: https://docs.moodle.org/dev/Javascript_Modules
//only functions which are exported will be callable from outside the module
//if the size of the params array is too large (> 1Kb), this will produce a developer warning.
//Do not attempt to pass large amounts of data through this function, it will pollute the page size.
//A preferred approach is to pass css selectors for DOM elements that contain data-attributes for any required data,
//or fetch data via ajax in the background.
//see: https://docs.moodle.org/dev/AJAX
import Ajax from 'core/ajax';
import notification from 'core/notification';

/**
 * Initialize feedback.
 * Only function which is callable from outside by PHP.
 * @param args associative array with roopath, courseid
 */
export const init = (args) => {
    addDocumentEventListeners();

    const rootPath = args.rootpath;
    const courseId = parseInt(args.courseid);
    displayFeedback(rootPath, courseId);
};

/**
 * Add necessary event listeners to document.
 * Hide popover
 * - if there was a click outside
 * - if 'esc' was pressed
 */
function addDocumentEventListeners() {
    //hide popover if click is outside the popover and the main feedback image
    //https://stackoverflow.com/questions/152975/how-do-i-detect-a-click-outside-an-element
    document.addEventListener("click", function(event) {
        let visiblePopover = document.querySelector("div.popover_content.popover_visible");
        // if visible popover exists and click was outside
        if (visiblePopover !== undefined && visiblePopover !== null
            && !visiblePopover.contains(event.target)) {
            let feedbacks = document.querySelectorAll("figure.block_activityfeedback_container");
            let isInside = false;
            // if click was inside a feedback container, don't set popover invisible
            for (let container of feedbacks) {
                if(container.contains(event.target)) {
                    isInside = true;
                    break;
                }
            }
            if (!isInside) { // click was outside popover and feedback container
                visiblePopover.classList.remove("popover_visible");
            }
        }
    });

    //close visible popover if escape is pressed
    document.addEventListener("keyup", function(event) {
        if (event.which === 27 || event.key === "Escape" || event.code === "Escape" || event.keyCode === 27) {
            let visiblePopover = document.querySelector("div.popover_content.popover_visible");
            if(visiblePopover !== undefined && visiblePopover !== null) {
                visiblePopover.classList.remove("popover_visible");
            }
        }
    });
}

/**
 * Display feedback options for each activity in given course.
 * @param rootPath root path needed for pix patch
 * @param courseid id of current course
 */
function displayFeedback(rootPath, courseid) {
    // get correct feedback pictures from backend
    Ajax.call([{
        methodname: 'block_activityfeedback_get_pix_data',
        args: {},
        done: function (pixData) {
            const activities = document.getElementsByClassName("activity");
            //add feedback elements for each activity
            for (const activity of activities) {
                let activityInstance = activity.getElementsByClassName("activityinstance")[0];
                //check if exists because e.g. activity 'label' has no <div> child element with class 'activityinstance'
                if (activityInstance !== undefined && activityInstance !== null) {
                    const courseModuleId = (activity.id).substring(7); //extract xx from id=module-xx
                    let container = document.createElement("figure");
                    container.className = "block_activityfeedback_container";
                    container.setAttribute("data-cmid", courseModuleId);
                    // create main image to open feedback options
                    // (= neutral clickable image which is shown before any feedback option is displayed/chosen)
                    let imgMain = document.createElement("img");
                    imgMain.className = "block_activityfeedback_img_main";
                    imgMain.src = rootPath + "/blocks/activityfeedback/pix/feedback.png";
                    imgMain.alt = "feedback";
                    imgMain.setAttribute("data-cmid", courseModuleId);
                    imgMain.addEventListener("click", function () {
                        openFeedbackOptions(this);
                    });

                    let popover = document.createElement("div");
                    // automatically defined as inline-block under activityinstance
                    popover.className = "popover_content";

                    //create figure element (with img and figcaption) for each feedback option
                    //there are 1 to max. 7 options, max. number of elements is given by length of pixData array
                    for (let num = 1; num <= 7 && num <= pixData.length; num++) {
                        let figureOpt = document.createElement("figure");
                        figureOpt.className = "block_activityfeedback_figopt";
                        figureOpt.setAttribute("data-cmid", courseModuleId);
                        figureOpt.setAttribute("data-fbid", pixData[num - 1].key);
                        figureOpt.setAttribute("data-fbname", pixData[num - 1].name);
                        figureOpt.addEventListener("click", function () {
                            setFeedbackOption(this, rootPath);
                        });

                        let imgOpt = document.createElement("img");
                        imgOpt.className = "block_activityfeedback_img";
                        imgOpt.src = pixData[num - 1].url;
                        imgOpt.alt = pixData[num - 1].name;

                        let figCaptOpt = document.createElement("figcaption");
                        figCaptOpt.textContent = pixData[num - 1].name;
                        figureOpt.append(imgOpt, figCaptOpt);
                        popover.append(figureOpt);
                    }
                    container.append(imgMain, popover);
                    activityInstance.appendChild(container);
                }
            }
            // update the displayed feedback
            getFeedback(rootPath, courseid);
        },
        fail: notification.exception
    }]);
}

/**
 * Display feedback options (popover),
 * is called by a click on a main feedback image.
 * @param mainImg neutral main feedback image which was clicked
 */
function openFeedbackOptions(mainImg) {
    let popover = mainImg.nextElementSibling;

    //toggle visibility of popover (set invisible if it's visible and vice versa)
    if (popover.classList.contains("popover_visible")) {
        popover.classList.remove("popover_visible");
    }
    else {
        //first set another possibly visible popover to invisible
        //because there should never be more than one popover visible at the same time
        let visiblePopover = document.querySelector("div.popover_content.popover_visible");
        if(visiblePopover !== undefined && visiblePopover !== null) {
            visiblePopover.classList.remove("popover_visible");
        }
        popover.classList.add("popover_visible");
    }
}

/**
 * Get existing feedback from server for given course and user
 * and display already selected/saved feedback options.
 * @param rootPath root path needed for pix path
 * @param courseid id of current course
 */
function getFeedback(rootPath, courseid) {
    //get feedback for current course and user from backend
    Ajax.call([{
        methodname: 'block_activityfeedback_get_feedback_data',
        args: {
            courseid: courseid
            //userid is checked serverside, better for security reasons
        },
        done: function (fbData) {
            let mainImgUpdated = false;

            // select correct image for each returned feedback item
            for (const fbItem of fbData) {
                //get feedback container for activity (course_modules_id) of returned feedback
                let container = document.querySelector(`figure.block_activityfeedback_container[data-cmid="${fbItem.cmid}"]`);
                if (container !== undefined && container !== null) { //needed if course_module was deleted
                    let mainImg = container.firstElementChild;
                    let figOptions = container.querySelectorAll("figure.block_activityfeedback_figopt");

                    for (let opt of figOptions) {
                        if (opt.getAttribute("data-fbid") == fbItem.fbid) { //feedback option was selected
                            mainImg.src = opt.firstElementChild.getAttribute("src"); //get src as it is, not resolved
                            mainImgUpdated = true;
                            opt.classList.remove("block_activityfeedback_not_selected");
                            opt.classList.add("block_activityfeedback_selected");
                        }
                        else { //feedback option was not selected
                            opt.classList.remove("block_activityfeedback_selected");
                            opt.classList.add("block_activityfeedback_not_selected");
                        }
                    }
                    if (!mainImgUpdated) {
                        mainImg.src = rootPath + "/blocks/activityfeedback/pix/feedback.png";
                        mainImgUpdated = true;
                    }
                }
            }
        },
        fail: notification.exception
    }]);
}

/**
 * Get existing feedback from server for a certain activity (and user)
 * and update displayed feedback options (selected / not selected).
 * Is called after feedback option was selected to update the view,
 * which is faster than getting all feedback data (getFeedback()) of the course.
 * @param rootPath root path used for pix path
 * @param cmid course_modules_id, identifies activity
 */
function getFeedbackForActivity(rootPath, cmid) {
    //get feedback for given activity from backend
    Ajax.call([{
        methodname: 'block_activityfeedback_get_feedback_activity',
        args: {
            cmid: cmid
            //userid is checked serverside, better for security reasons
        },
        done: function (fbData) {
            let mainImgUpdated = false;
            let fbItem = fbData[0]; //should return max. 1 feedback
            let container = document.querySelector(`figure.block_activityfeedback_container[data-cmid="${cmid}"]`);

            if (container !== undefined && container !== null) { //needed if course_module was deleted
                let mainImg = container.firstElementChild;
                let figOptions = container.querySelectorAll("figure.block_activityfeedback_figopt");

                for (let opt of figOptions) {
                    if (fbItem === undefined || fbItem === null) { //no feedback, if feedback option was deleted
                        opt.classList.remove("block_activityfeedback_selected");
                        opt.classList.remove("block_activityfeedback_not_selected");
                    }
                    else if (opt.getAttribute("data-fbid") == fbItem.fbid) { //feedback option was selected
                        mainImg.src = opt.firstElementChild.getAttribute("src"); //get src as it is, not resolved
                        mainImgUpdated = true;
                        opt.classList.remove("block_activityfeedback_not_selected");
                        opt.classList.add("block_activityfeedback_selected");
                    }
                    else { //feedback option was not selected
                        opt.classList.remove("block_activityfeedback_selected");
                        opt.classList.add("block_activityfeedback_not_selected");
                    }
                }
                if (!mainImgUpdated) {
                    mainImg.src = rootPath + "/blocks/activityfeedback/pix/feedback.png";
                    mainImgUpdated = true;
                }
            }
        },
        fail: notification.exception
    }]);
}

/**
 * Save chosen feedback option serverside and update the view.
 * Is called if a feedback option was selected.
 * @param btn figure of selected option
 * @param rootPath root path used for pix path
 */
function setFeedbackOption(btn, rootPath) {
    //popover shoudn't be visible anymore if feedback option was selected
    let popover = btn.parentElement;
    popover.classList.remove("popover_visible");
    let mainImg = popover.previousElementSibling;

    //Main feedback image should react fast and show correct image directly
    //therefore we update it before chosen feedback is saved and confirmed by the server.
    //if option was already selected it means user now has deselected it
    if (btn.classList.contains("block_activityfeedback_selected")) {
        mainImg.src = rootPath + "/blocks/activityfeedback/pix/feedback.png";
        btn.classList.remove("block_activityfeedback_selected");
    }
    //user has chosen a new or another feedback option
    else {
        mainImg.src = btn.firstElementChild.getAttribute("src");
        btn.classList.add("block_activityfeedback_selected");
    }

    const cmid = btn.getAttribute("data-cmid");
    const fbid = btn.getAttribute("data-fbid");
    const fbname = btn.getAttribute("data-fbname");

    //send selected feedback to server for storing in the database
    Ajax.call([{
        methodname: 'block_activityfeedback_set_feedback_data',
        args: {
            cmid: cmid,
            fbid: fbid,
            fbname: fbname
        },
        done: function () {
            //reload feedback for current activity
            getFeedbackForActivity(rootPath, cmid);
        },
        fail: notification.exception
    }]);
}