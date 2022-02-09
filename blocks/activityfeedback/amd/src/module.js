//https://docs.moodle.org/dev/Javascript_Modules
//only functions which are exported will be callable from outside the module
//if the size of the params array is too large (> 1Kb), this will produce a developer warning. Do not attempt
//to pass large amounts of data through this function, it will pollute the page size.
//A preferred approach is to pass css selectors for DOM elements that contain data-attributes for any required data,
//or fetch data via ajax in the background.
//https://moodle.org/mod/forum/discuss.php?d=378112#p1524459
// zwar veraltet, aber camel case hier ok: https://docs.moodle.org/dev/Javascript/Coding_Style
// (auch in Bsp benutzt: https://docs.moodle.org/dev/Javascript_Modules)
//todolig auf camelCase umstellen
//import $ from 'jquery';
//import jquery from 'jquery';
//import * as Str from 'core/str';
import Ajax from 'core/ajax';
//import {exception as displayError} from 'core/notification';
import notification from 'core/notification';

//siehe backpackactions.js
// import $ from 'jquery';
// import selectors from 'core_badges/selectors';
// import {get_string as getString} from 'core/str';
// import Pending from 'core/pending';
// import ModalFactory from 'core/modal_factory';
// import ModalEvents from 'core/modal_events';

// was not usable and global const not recommended
// import Config from 'core/config';


//define(['jquery', 'core/ajax', 'core/notification']);
export const init = (args) => {
    const userid = parseInt(args.userid);
    const courseid = parseInt(args.courseid);
    const rootPath = args.rootpath;
    //const elems = document.querySelectorAll('[id^=module-]');
    //warum fkt. hier jquery?
    //jquery: .done(), .success, .fail, .always
    //.then, .when
    //window.console.log(Config.wwwroot);
    //import gibt Fehler
    //https://docs.moodle.org/dev/Useful_core_Javascript_modules
    //https://stackoverflow.com/questions/5915258/how-to-load-external-js-file-into-moodle

    //window.rootPath = args.rootpath;
    //require:
    //https://docs.moodle.org/dev/AJAX
    //import:
    //https://docs.moodle.org/dev/Javascript_Modules
    //Uncaught undefined
    displayPictures(rootPath, courseid, userid);
    //getFeedback(courseid, userid); // todolig! gibt hier race condition, dass getFeedback end done noch vor displayPict start done
    window.console.log("nach display");
};

//const displayPictures = (rootPath, courseid, userid) =>
function displayPictures(rootPath, courseid, userid)
{
    window.console.log("displayPictures: start");
    Ajax.call([
        {
            methodname: 'block_activityfeedback_get_pix_data',
            args: {},
            done: function (pixData) {
                window.console.log("displayPictures: start done");
                const activities = document.getElementsByClassName("activity");
                let nummer = 0;
                for (const activity of activities)
                {
                    nummer = nummer + 1;
                    window.console.log("activity " + nummer);
                    let activityInstance = activity.getElementsByClassName("activityinstance")[0];
                    // check if exists, because e.g. activity 'label' has no <div> child element with class 'activityinstance'
                    if(activityInstance !== undefined && activityInstance !== null) {
                        const courseModuleId = (activity.id).substring(7); // extract xx from id=module-xx
                        let container = document.createElement("figure");
                        container.className = "block_activityfeedback_container";
                        container.setAttribute("data-cmid", courseModuleId);
                        //node.classList.add('MyClass');
                        // main button for feedback
                        //let figureMain = document.createElement("figure");
                        let imgMain = document.createElement("img");
                        imgMain.className = "block_activityfeedback_btn_main";
                        imgMain.src = rootPath + "/blocks/activityfeedback/pix/feedbackmain.png";
                        //todo: bild eckig via css rund machen
                        //imgMain.alt = Str.get_string('activityfeedback', 'block_activityfeedback'); //todolig
                        imgMain.setAttribute("data-cmid", courseModuleId);
                        //let figCaptMain = document.createElement("figcaption");
                        //figCaptMain.textContent = Str.get_string('activityfeedback', 'block_activityfeedback');
                        //window.console.log(Str.get_string('activityfeedback', 'block_activityfeedback'));
                        //window.console.log(formal);
                        //figureMain.append(imgMain, figCaptMain);
                        //container.append(figureMain);

                        //Achtung: neu ist glaube ich scrollbalken wegen Bildern, auch senkrecht innerhalb!


                        imgMain.classList.add("popover_title");
                        container.classList.add("popover_wrapper");
                        let popover = document.createElement("figure");
                        //figure statt div, weil div unter activityinstance ist css vorgeschrieben: inline-block
                        popover.className = "popover_content";
                        //popover.textContent = "text text text";
                        //evtl. noch if isvisible:
                        //https://stackoverflow.com/questions/152975/how-do-i-detect-a-click-outside-an-element
                        //clicklistener entfernen?
                        //https://developer.mozilla.org/en-US/docs/Web/API/Element/closest
                        //bei Klick ausserhalb schliessen: //https://www.w3schools.com/howto/howto_css_modals.asp
                        //https://stackoverflow.com/questions/152975/how-do-i-detect-a-click-outside-an-element
                        //https://css-tricks.com/dangers-stopping-event-propagation/
                        document.addEventListener("click", function(event) {
                            if(!popover.contains(event.target) && !container.contains(event.target))
                            {
                                //hide if click is outside popover and main feedback button
                                popover.classList.remove("popover_visible");
                                window.console.log("klick nicht auf popover");
                            }
                            else {
                                window.console.log("klick ist auf popover");
                            }
                        });
                        //close visible popover if escape is pressed
                        document.addEventListener("keyup", function(event) {
                            if (event.which === 27 || event.key === "Escape" || event.code === "Escape" || event.keyCode === 27) {
                                let visiblePopover = document.querySelector("figure.popover_content.popover_visible");
                                if(visiblePopover !== undefined && visiblePopover !== null) {
                                    visiblePopover.classList.remove("popover_visible");
                                }
                            }
                        });

                        // feedback buttons for option 1 to max. 7, max. number of elements is given by length of pixData array
                        for (let num = 1; num <= 7 && num <= pixData.length; num++) {
                            let figureOpt = document.createElement("figure");
                            figureOpt.id = "figureopt" + num;//todolig
                            figureOpt.className = "block_activityfeedback_figopt";
                            let imgOpt = document.createElement("img");
                            imgOpt.className = "block_activityfeedback_btn";
                            imgOpt.src = pixData[num - 1].url;
                            imgOpt.alt = pixData[num - 1].name;
                            imgOpt.setAttribute("data-cmid", courseModuleId);
                            imgOpt.setAttribute("data-fbid", pixData[num - 1].key);
                            imgOpt.setAttribute("data-fbname", pixData[num - 1].name);
                            let figCaptOpt = document.createElement("figcaption");
                            figCaptOpt.textContent = pixData[num - 1].name;
                            figureOpt.append(imgOpt, figCaptOpt);
                            //figureOpt.append(imgOpt);
                            popover.append(figureOpt);
                            //popover.append(imgOpt);
                            //container.append(figureOpt);
                        }

                        container.append(imgMain, popover);

                        //statt innerHTML immer textContent verwenden aus Sich.gründen bei rein Text
                        activityInstance.appendChild(container);

                        window.console.log("activity inner end " + nummer);
                    }
                    window.console.log("activity outer end " + nummer);
                }
                // main buttons for feedbacks
                // (= neutral button which is shown before any feedback button is chosen)
                window.console.log("fbMainBtns 1");
                let fbMainBtns = document.getElementsByClassName("block_activityfeedback_btn_main");
                window.console.log("fbMainBtns 2");
                for (let btn of fbMainBtns) {
                    btn.addEventListener('click', function () {
                        openFeedback(this);
                    });
                }
                window.console.log("after fbMainBtns ");
                // feedback buttons
                let fbBtns = document.getElementsByClassName("block_activityfeedback_btn");
                for (let btn of fbBtns) {
                    btn.addEventListener('click', function () {
                        setFeedback(rootPath, this,courseid,userid);
                    });
                }
                window.console.log("after fbBtns ");
                // muss hier im done sein, weil sonst evtl. Attribut nicht gefunden wird!
                getFeedback(rootPath, courseid, userid);
                window.console.log("after getFeedback ");
                //});
                window.console.log("displayPictures: end done");
            }, //).catch(Notification.exception); //
            fail: notification.exception
            //todolig: unklar wann .fail/.catch, wann hier mit Doppelpunkt
        }
    ]);
    window.console.log("displayPictures: end");
}
function openFeedback(mainBtn)
{
    const cmId = mainBtn.getAttribute("data-cmid");
    window.console.log("openFeedback");
    window.console.log(cmId);
    //siehe https://docs.moodle.org/dev/Web_service_API_functions
    //mod_data für DB-Zugriffe, aber nicht via Ajax:( z.B. mod_data_add_entry
    //mainBtn.classList.add("popover_visible");
    let popover = mainBtn.nextElementSibling;
    //toggle visible
    if(popover.classList.contains("popover_visible"))
    {
        popover.classList.remove("popover_visible");
    }
    else {
        //set first other visible popover to not visible
        // so that never two popovers are visible at the same time
        let visiblePopover = document.querySelector("figure.popover_content.popover_visible");
        if(visiblePopover !== undefined && visiblePopover !== null) {
            visiblePopover.classList.remove("popover_visible");
        }
        popover.classList.add("popover_visible"); //toggle
    }
}
function getFeedback(rootPath, courseid, userid) {
    window.console.log("getFeedback: start");
    Ajax.call([
        {
            methodname: 'block_activityfeedback_get_feedback_data',
            args: {
                courseid: courseid
            },
            done: function (fbData) {
                window.console.log("getFeedback: start done");

                let isBtnMainUpdated = false;

                //https://developer.mozilla.org/de/docs/Web/JavaScript/Reference/Global_Objects/Array/forEach
                //ajaxResult0.forEach(function(fbitem) {
                //siehe generell als Tipp: https://www.learningrobo.com/2021/10/modern-feedback-form-using-html-css.html
                for (const fbItem of fbData) {
                    if (fbItem.userid === userid) {
                let container = document.querySelector(`figure.block_activityfeedback_container[data-cmid="${fbItem.cmid}"]`);
                        let btnMain = document.querySelector(`img.block_activityfeedback_btn_main[data-cmid="${fbItem.cmid}"]`);
                        //container sollte es nur 1 geben mit dieser id
                        // document.getElementsByClassName("block_activityfeedback_container")
                        // let fbnotselected = container.getElementsByClassName("block_activityfeedback_btn")
                        //     .not(`[data-fbid="{fbitem.fbid}"]`);
                        // //todolig: zuerst entfernen
                        // fbnotselected.classList.remove("block_activityfeedback_selected");
                        // fbnotselected.classList.add("block_activityfeedback_not_selected");
                        // let fbselected = container.getElementsByClassName("block_activityfeedback_btn")
                        //     .querySelector(`[data-fbid="{fbitem.fbid}"]`);

                        // statt hinzu/entfernen mit toggle: x.classList.toggle("fa-thumbs-down");
                        //https://www.w3schools.com/howto/howto_js_toggle_like.asp
                        if (container !== undefined && container !== null) { // needed if course_module was deleted
                            let fbPix = container.querySelectorAll("img.block_activityfeedback_btn");


                            for (let pix of fbPix) {
                                if (pix.getAttribute("data-fbid") == fbItem.fbid) {
                                    window.console.log("pix true");
                                    btnMain.src = pix.getAttribute("src"); //get src as it is, not resolved
                                    isBtnMainUpdated = true;
                                    pix.classList.remove("block_activityfeedback_not_selected");
                                    pix.classList.add("block_activityfeedback_selected");
                                } else {
                                    window.console.log("pix false");
                                    pix.classList.remove("block_activityfeedback_selected");
                                    pix.classList.add("block_activityfeedback_not_selected");
                                }
                            }
                            if(!isBtnMainUpdated)
                            {
                                btnMain.src = rootPath + "/blocks/activityfeedback/pix/feedbackmain.png";
                                isBtnMainUpdated = true;
                            }
                        }
                    }
                }//);

                // auch falls zu kurs/user gar nichts auf DB
                //if no feedback option is set, reset main button
                // if (!isBtnMainUpdated) {
                //     //todolig: import core/config nicht funktioniert, daher globa
                //     //alternativ als param an jed. Fkt. übergeb
                //     let fbMainBtns = document.getElementsByClassName("block_activityfeedback_btn_main");
                //     for (let btn of fbMainBtns) {
                //         btn.src = window.rootPath + "/blocks/activityfeedback/pix/feedbackmain.png";
                //     }
                // }


                window.console.log("getFeedback: end done");
            },
            fail: notification.exception
            //todolig: unklar wann .fail/.catch, wann hier mit Doppelpunkt
        }
    ]);
    window.console.log("getFeedback: end");
}
function getFeedbackForActivity(rootPath, cmid, userid) {
    window.console.log("getFeedbackForActivity: start");
    window.console.log(userid); // todolig userid ganz entfernen, spätere Prüfung bereits entfernt

    Ajax.call([
        {
            methodname: 'block_activityfeedback_get_feedback_activity',
            args: {
                cmid: cmid
            },
            done: function (fbData) {
                window.console.log("getFeedbackForActivity: start done");

                let isBtnMainUpdated = false;

                let fbItem = fbData[0];

                let btnMain = document.querySelector(`img.block_activityfeedback_btn_main[data-cmid="${cmid}"]`);

                //if(fbItem !== undefined && fbItem !== null && fbItem.userid === userid) {
                //if(fbItem.userid === userid)
                    let container = document.querySelector(`figure.block_activityfeedback_container[data-cmid="${cmid}"]`);

                    // statt hinzu/entfernen mit toggle: x.classList.toggle("fa-thumbs-down");
                    //https://www.w3schools.com/howto/howto_js_toggle_like.asp
                    if (container !== undefined && container !== null) { // needed if course_module was deleted
                        let fbPix = container.querySelectorAll("img.block_activityfeedback_btn");

                        for (let pix of fbPix) {
                            if (fbItem === undefined || fbItem === null) {
                                window.console.log("pix neutral");
                                pix.classList.remove("block_activityfeedback_selected");
                                pix.classList.remove("block_activityfeedback_not_selected");
                            }
                            else if (pix.getAttribute("data-fbid") == fbItem.fbid) {
                                window.console.log("pix true");
                                btnMain.src = pix.getAttribute("src"); //get src as it is, not resolved
                                isBtnMainUpdated = true;
                                pix.classList.remove("block_activityfeedback_not_selected");
                                pix.classList.add("block_activityfeedback_selected");
                            }
                            else {
                                window.console.log("pix false");
                                pix.classList.remove("block_activityfeedback_selected");
                                pix.classList.add("block_activityfeedback_not_selected");
                            }
                        }
                    }
                //}

                if(!isBtnMainUpdated)
                {
                    btnMain.src = rootPath + "/blocks/activityfeedback/pix/feedbackmain.png";
                    isBtnMainUpdated = true;


                }

                // auch falls zu kurs/user gar nichts auf DB
                //if no feedback option is set, reset main button
                // if (!isBtnMainUpdated) {
                //     //todolig: import core/config nicht funktioniert, daher globa
                //     //alternativ als param an jed. Fkt. übergeb
                //     let fbMainBtns = document.getElementsByClassName("block_activityfeedback_btn_main");
                //     for (let btn of fbMainBtns) {
                //         btn.src = window.rootPath + "/blocks/activityfeedback/pix/feedbackmain.png";
                //     }
                // }


                window.console.log("getFeedback: end done");
            },
            fail: notification.exception
            //todolig: unklar wann .fail/.catch, wann hier mit Doppelpunkt
        }
    ]);
    window.console.log("getFeedback: end");
}
function setFeedback(rootPath, btn,courseid,userid)
{
    window.console.log("setFeedback: start");
    //siehe https://docs.moodle.org/dev/Web_service_API_functions
    //mod_data für DB-Zugriffe, aber nicht via Ajax:( z.B. mod_data_add_entry

    // for fast reaction, todolig pruefen
    //this.classList.remove("block_activityfeedback_not_selected");
    //evtl. mit toggle, auch deselekt., evtl. sieht man gar nicht mehr
    btn.classList.add("block_activityfeedback_selected");

    const cmid = btn.getAttribute("data-cmid");
    const fbid = btn.getAttribute("data-fbid");
    const fbname = btn.getAttribute("data-fbname");

    //popover not visible if feedback option was selected
    let popover = btn.parentElement.parentElement;
    popover.classList.remove("popover_visible");
    //mainBtn auch grad direkt setzen ohne Server AW
    //todolig: aber delete?
    let mainBtn = popover.previousElementSibling;
    mainBtn.src = btn.getAttribute("src");

    Ajax.call([
        {
            methodname: 'block_activityfeedback_set_feedback_data',
            args: {
                cmid: cmid, //event.data.moduleId,
                fbid: fbid,//event.data.reactionSelect,
                fbname: fbname
                // sql function (insert/update/delete)
                // is defined by server
            },
            done: function () {
                window.console.log("setFeedback: start done");
                //getFeedback(courseid,userid);
                getFeedbackForActivity(rootPath, cmid, userid);
                window.console.log("setFeedback: end done");
            },
            fail: notification.exception
            //todolig: unklar wann .fail/.catch, wann hier mit Doppelpunkt
        }
    ]);
    window.console.log("setFeedback: end");
}
//todolig: fuer str.get_string, fkt. zwar, aber gibt neu davor Exception: testjs.js:1 Uncaught undefined
// https://docs.moodle.org/dev/Useful_core_Javascript_modules#Language_strings_.28core.2Fstr.29
// require(['core/str'], function(str) {
//     // start retrieving the localized string; store the promise that some time in the future the string will be there.
//     var editaPresent = str.get_string('activityfeedback', 'block_activityfeedback');
//     // as soon as the string is retrieved, i.e. the promise has been fulfilled,
//     // edit the text of a UI element so that it then is the localized string
//     // Note: $.when can be used with an arbitrary number of promised things
//     jQuery.when(editaPresent).done(function(localizedEditString) {
//         window.console.log(localizedEditString);
//     });
// });

//https://docs.moodle.org/dev/AJAX

//https://docs.moodle.org/dev/Javascript_Modules
//const formal = () => Str.get_string('activityfeedback', 'block_activityfeedback', '');