<?php $Database_communication = app('App\Http\Backend\Database_communication'); ?>
<?php $userInterface = app('App\Http\Middleware\CourseUserInterface'); ?>
<?php
$db = $Database_communication;

$dataOnlineClass = $db->getCoursesClassGeneralDataByIdCoursesClass($idCoursesClass)->first();
$dataTopic = $db->getCoursesClassTopicByIdTopic($idTopic)->first();
$dataSubTopic = $db->getCoursesClassSubTopicByIdSubTopic($idSubTopic)->first();
$dataAssignment = $db->getCoursesClassSubTopicAssignmentByIdSubTopic($idSubTopic)->get();

$no = 1;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h2>Topic : <?php echo $dataTopic->TopicName; ?></h2></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info">
            <center><h4>Sub Topic : <?php echo $dataSubTopic->subTopicName; ?></h4></center>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <!-- Example Bar Chart Card-->
        <div class="card mb-3">
            <div class="card-header bg-success">
                <i class="fa fa-user-circle-o"></i> Assignments List</div>
            <div class="card-body">
                <button onclick="location.href='<?php echo URL::to('/'); ?>/myCourse/enterClass/<?php echo $idCoursesClass; ?>/enterSession/<?php echo $idTopic; ?>'" class="btn btn-warning">Back</button>
                <br />
                <br />
                <table class="table table-hovered">
                    <thead>
                    <tr class="table-info">
                        <th>No.</th>
                        <th>Actions</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Deadline</th>
                        <th>Is Required (Yes/No)</th>
                        <th>Score Range</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(count($dataAssignment) == 0): ?>
                        <tr class="table-danger"><td colspan="9"><center>NO ASSIGNMENT</center></td></tr>
                    <?php else: ?>
                        <?php $__currentLoopData = $dataAssignment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            // Code to find the material uploader / creator
                            $dataCreator = $db->getMemberData($data->idUser)->first();
                            $idAuthorityCreator = $dataCreator->idAuthority;

                            $dataCreator = $db->getFullMemberData($data->idUser, $idAuthorityCreator)->first();

                            if($idAuthorityCreator == "3"){
                                $creatorName = $dataCreator->nama_dosen;
                            }else{
                                $creatorName = $dataCreator->name;
                            }

                            $getAssignmentCompletion = $db->getAssignmentCompletionByIdAssignmentAndIdMember($data->idAssignment, session('idMember'));
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <table border="0">
                                        <tr>
                                            <td><button onclick="enterAssignment(<?php echo $data->idAssignment; ?>,'<?php echo $data->assignmentType; ?>')" class="btn btn-primary"><i class="fa fa-sign-in" aria-hidden="true"></i></button></td>
                                        </tr>
                                    </table>
                                </td>
                                <td><?php echo $creatorName; ?></td>
                                <td><?php echo date('Y M d H:i:s',strtotime($data->dateTime)); ?> GMT +7</td>
                                <td><?php echo strtoupper($data->assignmentType); ?></td>
                                <td><?php echo $data->assignmentDescription; ?></td>
                                <td><?php echo date('d M Y H:i:s', strtotime($data->assignmentDeadline)); ?> GMT +7</td>
                                <?php if($data->isRequired == "1"): ?>
                                    <td>Yes</td>
                                <?php else: ?>
                                    <td>No</td>
                                <?php endif; ?>
                                <td><?php echo $data->scoreRangeStart; ?> - <?php echo $data->scoreRangeEnd; ?></td>

                                <?php if($getAssignmentCompletion->count() == 0): ?>
                                    <td><span class="badge badge-danger">Incomplete</span></td>
                                <?php else: ?>
                                    <?php
                                        $getScore = $db->getTotalAssignmentScoreByIdAssignmentAndIdMember($data->idAssignment, session('idMember'));
                                    ?>
                                    <td>
                                        <span class="badge badge-success">Completed</span>
                                        <span class="badge badge-info">Score <?php echo $getScore; ?></span>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" data-keyboard="false" data-backdrop="static" id="myCreateAssignmentModals" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content panel-info">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myCreateAssignmentModalsLabel"></h4>
            </div>
            <div class="modal-body" id="myCreateAssignmentModalsBody">
                <form method="post" action="<?php echo URL::to('/'); ?>/manageOnlineCourse/availableClass/manageOnlineClass/<?php echo $idCoursesClass; ?>/manageSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/manageAssignment/submitCreateAssignment" class="form-horizontal">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="idCoursesClass" value="<?php echo $idCoursesClass; ?>">
                    <input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>">
                    <input type="hidden" name="idSubTopic" value="<?php echo $idSubTopic; ?>">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Assignment Type :</label>
                        <div class="col-sm-12">
                            <select name="typeAssignment" class="form-control">
                                <option value="written">Written</option>
                                <option value="upload">Upload File</option>
                                <option value="choices">Multiple Choices</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Description :</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" id="description" name="description"></textarea>
                            <script>
                                CKEDITOR.replace( 'description');
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Deadline :</label>
                        <div class="col-sm-6">
                            <input type="text" name="deadline" id="deadline" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Is Required :</label>
                        <div class="col-sm-3">
                            <select class="form-control" id="isRequired" name="isRequired">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Minimum Score :</label>
                        <div class="col-sm-4">
                            <input type="number" name="minScore" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-8" for="email">Maximum Score :</label>
                        <div class="col-sm-4">
                            <input type="number" name="maxScore" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                <script language="JavaScript">
                    $(document).ready(function() {
                        loadDateTimePicker('deadline','yyyy-mm-dd hh:ii');
                    });
                </script>
            </div>
            <div class="modal-footer" id="myCreateAssignmentModalsExtraButton">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script language="JavaScript">
    function enterAssignment(idAssignment, typeAssignment){
        location.href='/myCourse/enterClass/<?php echo $idCoursesClass; ?>/enterSession/<?php echo $idTopic; ?>/<?php echo $idSubTopic; ?>/enterAssignment/'+idAssignment+'/'+typeAssignment;
    }
</script>
