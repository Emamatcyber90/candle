<?php

slot('title', 'Aktuálna výučba');

slot('top');
include_component('timetable','top');
end_slot();

slot('kiosk_vrch');
?>
<h1 style="display: inline">Aktuálna výučba</h1>
prebiehajúca o <?php echo date('H:i', $queryTime); ?>
<div style="clear: both"></div>
<?php
end_slot();

if (count($lessonIntervals) == 0):
    echo 'Momentálne neprebieha žiadna výučba.';
else:

$cols = array(0, count($lessonIntervals) / 2, count($lessonIntervals));
for ($j = 0; $j < count($cols) - 1; $j++):
?>
<table class="aktualne <?php echo 'stlpec' . $j ?>">
    <thead>
        <tr><th>Čas</th><th>Miestnosť</th><th>Hodina</th></tr>
    </thead>
    <tbody>
<?php
for ($i = $cols[$j]; $i < $cols[$j + 1]; $i++) {
    $lesson = $lessonIntervals[$i];
    echo '<tr>';
    echo '<td>';
    echo Candle::formatTime($lesson['start'], true);
    echo '&#x2011;';
    echo Candle::formatTime($lesson['end'], true);
    echo '</td>';
    echo '<td>';
    echo link_to($lesson['Room']['name'], array('sf_route'=>'room_show', 'id'=>$lesson['Room']['id']));
    echo '</td>';
    echo '<td>';
    ?><abbr class="lesson-type <?php echo Candle::getLessonTypeHTMLClass($lesson['LessonType']); ?>" title="<?php echo $lesson['LessonType']['name']; ?>"><span class="lesson-type-in"><?php echo $lesson['LessonType']['code'] ?></span><span class="lesson-type-image"></span></abbr> <?php
    // TODO(anty): refactor this as it is duplicated
    $shortCode = $lesson['Subject']['short_code'];
    $subjectInfoLink = Candle::makeSubjectInfoLink($shortCode);
    $subjectName = $lesson['Subject']['name'];
    if ($subjectInfoLink) {
        $subjectName = link_to($subjectName, $subjectInfoLink, array('class'=>'subjectName'));
    }
    else {
        $subjectName = '<span class="subjectName">'.$subjectName.'</span>';
    }
    echo $subjectName;
    $first = true;
    foreach ($lesson['Teacher'] as $teacher) {
        $teacherName = Candle::formatShortName($teacher);
        if ($teacherName == '') continue;
        if ($first) {
            echo ' - <i>';
        }
        else {
            echo ', ';
        }
        $first = false;
        echo link_to($teacherName, array('sf_route'=>'timetable_teacher_show', 'id'=>$teacher['id']));
    }
    if (!$first) {
        echo '</i>';
    }
    echo '</td>';
    
    echo '</tr>';
    
}
?>
    </tbody>
</table>
<?php endfor;
endif;