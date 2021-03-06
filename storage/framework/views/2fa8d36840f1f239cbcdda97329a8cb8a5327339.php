<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$getOnlineClassMentor = $db->getOnlineClassMentorByIdCoursesClass($idCoursesClass)->get();
$getAvailableMentor = $db->getAvailableMentorForOnlineClassByIdCoursesClass($idCoursesClass)->get();
?>
<div class="row">
    <div class="col-lg-12">
        <form method="post" action="<?php echo URL::to('/'); ?>/ServerSide/ManageOnlineClass/addOnlineClassOverview/submit" class="form-horizontal">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
            <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
            <div class="form-group">
                <label class="control-label col-sm-4" for="email">Course Overview :</label>
                <div class="col-sm-12">
                    <textarea class="form-control" name="courseOverview"></textarea>
                    <script>
                        CKEDITOR.replace( 'courseOverview');
                    </script>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-10">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>


