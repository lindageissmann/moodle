<?php
function block_simplehtml_images() {
    return array(html_writer::tag('img', '', array('alt' => get_string('red', 'block_simplehtml'), 'src' => "pix/1.png")),
            html_writer::tag('img', '', array('alt' => get_string('blue', 'block_simplehtml'), 'src' => "pix/2.png")),
            html_writer::tag('img', '', array('alt' => get_string('green', 'block_simplehtml'), 'src' => "pix/3.png")));
}

// To avoid any code duplication and make it easy to reuse this functionality create a function in lib.php
// to handle the page display. A preloaded simplehtml page is passed in as a single parameter,
// and an optional parameter will control whether the data is returned or directly printed out. 
// false = printed out
function block_simplehtml_print_page($simplehtml, $return = false) {
    // To display the page title use the $OUTPUT class
    global $OUTPUT, $COURSE;
    $display = $OUTPUT->heading($simplehtml->pagetitle);

    // After we have displayed the title, let's add a box to put around the rest of
    // the elements that we will display. 
    $display .= $OUTPUT->box_start();

    if($simplehtml->displaydate) {
        // put a div tag around the date, and give it a class to target with CSS later
        $display .= html_writer::start_tag('div', array('class' => 'simplehtml displaydate'));
        $display .= userdate($simplehtml->displaydate);
        $display .= html_writer::end_tag('div');
    }

    // display text
    $display .= clean_text($simplehtml->displaytext);

    //close the box
    $display .= $OUTPUT->box_end();

    // display the picture
    if ($simplehtml->displaypicture) {
        $display .= $OUTPUT->box_start();
        $images = block_simplehtml_images();
        $display .= $images[$simplehtml->picture];
        $display .= html_writer::start_tag('p');
        $display .= clean_text($simplehtml->description);
        $display .= html_writer::end_tag('p');
        $display .= $OUTPUT->box_end();
    }

    if($return) {
        return $display;
    } else {
        echo $display;
    }

    
}
