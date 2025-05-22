<!-- Add Election Modal -->
<div class="modal fade" id="addElection">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Add New Election</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="election_add.php">
                    <div class="form-group">
                        <label for="title" class="col-sm-3 control-label">Election Title</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start_time" class="col-sm-3 control-label">Start Time</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="end_time" class="col-sm-3 control-label">End Time</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
                <button type="submit" class="btn btn-primary btn-flat" name="addElection">
                    <i class="fa fa-save"></i> Save Election
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Election Modal -->
<div class="modal fade" id="editElection">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Edit Election</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="election_edit.php">
                    <input type="hidden" class="id" name="id">
                    <div class="form-group">
                        <label for="edit_title" class="col-sm-3 control-label">Election Title</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_start_time" class="col-sm-3 control-label">Start Time</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="edit_start_time" name="start_time" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_end_time" class="col-sm-3 control-label">End Time</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="edit_end_time" name="end_time" required>
                        </div>
                    </div>
                    <div class="form-group">
                        
                        <div class="col-sm-9">

                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
                <button type="submit" class="btn btn-success btn-flat" name="editElection">
                    <i class="fa fa-check-square-o"></i> Update Election
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Election Modal -->
<div class="modal fade" id="deleteElection">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Deleting Election...</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="election_delete.php">
                    <input type="hidden" class="id" name="id">
                    <div class="text-center">
                        <p>DELETE ELECTION</p>
                        <h2 class="bold election-title"></h2>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
                <button type="submit" class="btn btn-danger btn-flat" name="deleteElection">
                    <i class="fa fa-trash"></i> Delete
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Election Modal -->
<div class="modal fade" id="viewElection">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Election Details</b></h4>
            </div>
            <div class="modal-body">
                <p><strong>Title:</strong> <span id="electionTitle"></span></p>
                <p><strong>Start Time:</strong> <span id="electionStart"></span></p>
                <p><strong>End Time:</strong> <span id="electionEnd"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">
                    <i class="fa fa-close"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
