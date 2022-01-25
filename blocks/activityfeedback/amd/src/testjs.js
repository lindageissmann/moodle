//https://docs.moodle.org/dev/Javascript_Modules
//only functions which are exported will be callable from outside the module
//if the size of the params array is too large (> 1Kb), this will produce a developer warning. Do not attempt
//to pass large amounts of data through this function, it will pollute the page size.
//A preferred approach is to pass css selectors for DOM elements that contain data-attributes for any required data,
//or fetch data via ajax in the background.
//https://moodle.org/mod/forum/discuss.php?d=378112#p1524459
//import jQuery from 'jquery';
//----import * as Str from 'core/str';
//import Ajax from 'core/ajax';
export const init = (first) => {
    window.console.log('we have been started_blabla_linda');
    window.console.log(`hello ${first} , warum?`);
    //window.console.log(Str.get_string('defaulttext','block_activityfeedback'));
    //alert(`guten tag ${first}`); //fkt. ohne import jquery
    const elem = document.getElementById("module-1");
    window.console.log(elem.innerHTML);
    //const elems = document.querySelectorAll('[id^=module-]');
    const activities = document.getElementsByClassName("activity");
    for (const activity of activities)
    {
        let activityInstance = activity.getElementsByClassName("activityinstance")[0];
        let container = document.createElement("div");
        container.className = "block_activityfeedback_container";
        //node.classList.add('MyClass');
        let figureMain = document.createElement("figure");
        let imgMain = document.createElement("img");
        //imgMain.src = "../pix/thumbsup.png";
        //imgMain.src = "[[pix:theme|../pix/thumbsup]]";
        imgMain.src = "pix/thumbsup.png";
        imgMain.alt = "feedback button";
        let figCaptMain = document.createElement("figcaption");
        figCaptMain.textContent = "Feedback";
        figureMain.append(imgMain, figCaptMain);
        container.append(figureMain);
        activityInstance.appendChild(container);
    }
};