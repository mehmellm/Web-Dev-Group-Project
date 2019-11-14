<?php
$text = file_get_contents("questions.txt");
$titles = array();
$questions = array();
$answers = array();
foreach (explode("***", $text) as $question) {
     $pieces = explode(";",$question);
     $titles[]= $pieces[0];
     $question_text = trim($pieces[1]);
     if ($question_text == "*")        // if they used the title as the text
          $question_text = $pieces[0]; // then there is a * in the question
     $questions[]= $question_text;
     $answers[]= $pieces[2];
}

$num_to_show = min(count($titles),25);
print("<select size=$num_to_show>\n");
$n = 0;

foreach ($titles as $title) {
     print("<option onclick='select_question(" . $n . ")'>");
     print(trim($title));
     print("\n</option>\n");
     $n++;
}
print("\n</select>");
