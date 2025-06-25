<style>
    .table tr td {
        vertical-align: middle !important;
    }
</style>
<div class="main-content" ng-controller="user_role" id="user_role">
    <section class="section">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card-bg"></div>
                <div class="row mt-2">
                    <div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
                        <h3><?= $title ?></h3>
                        <span>Manajemen User > User Role</span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:37px;">
                        <?php if ($users['role_access']['user_role']['accessadd_user_role'] == 'on') { ?>
                        <form class="form-inline float-right">
                            <div class="mb-2 mr-2">
                                <a href="" class="btn btn-light btn-xl" style="float: right;" title="Tambah Data" ng-click="tambah()">
                                    <i class="fas fa-plus"></i> Tambah
                                </a>
                            </div> 
                        </form>
                        <?php } ?>
                    </div>
                </div>
                <div class="card card-statistic-2">
                    <div class="card-stats p-3">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div ng-if="pending">
                                    <center><img src="<?= base_url() ?>assets/img/loadertsel.gif" alt="img-fluid"> Loading...</center>
                                </div>
                                <div class="table-responsive" ng-if="!pending">
                                    <table class="table table-striped table-md" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-center">Nama Role</th>
                                                <th class="text-center">Tanggal Dibuat</th>
                                                <?php if ($users['role_access']['user_role']['accessedit_user_role'] == 'on' || $users['role_access']['user_role']['accessdelete_user_role'] == 'on') { ?>
                                                    <th class="text-center"></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr dir-paginate="(key, value) in itemData|itemsPerPage:itemsPerPage" total-items="totalData" current-page="currentPage" pagination-id="paginateID">
                                                <td style="font-size: 10px;" class="text-center" ng-bind="key+no"></td>
                                                <td ng-bind="value.name"></td>     
                                                <td class="text-center" ng-bind="value.created_at"></td>
                                                <?php if ($users['role_access']['user_role']['accessedit_user_role'] == 'on' || $users['role_access']['user_role']['accessdelete_user_role'] == 'on') { ?>
                                                    <td>
                                                        <?php if ($users['role_access']['user_role']['accessedit_user_role'] == 'on') { ?>
                                                            <a href="<?= base_url() . 'user_role/edit_role/' ?>{{value.id}}">
                                                                <button type="button" class="btn btn-warning btn-sm btn-save-family" title="Edit"> 
                                                                    <i class="fas fa-pencil-alt"></i>
                                                                </button>
                                                            </a>
                                                        <?php }?>
                                                        <?php if ($users['role_access']['user_role']['accessdelete_user_role'] == 'on') { ?>
                                                            <a href="#">
                                                                <button type="button" class="btn btn-danger btn-sm btn-save-family" ng-click="delete(value.id)" title="Delete" ng-show="value.id !='1' && value.id !='2'"> 
                                                                    <i class="fas fa-trash"></i>&nbsp;
                                                                </button>
                                                            </a>
                                                        <?php }?>
                                                    </td>
                                                <?php }?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <dir-pagination-controls 
                                    direction-links="true" 
                                    pagination-id="paginateID"
                                    boundary-links="true" 
                                    on-page-change="getCandidate(newPageNumber)">
                                </dir-pagination-controls>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="mdlInvite" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdlInviteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mdlInviteLabel">Send Invitation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="email_candidate">Email address</label>
                                    <input type="email" class="form-control" id="email_candidate" ng-model="candidate.email" aria-describedby="emailHelp" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="start_date">Date</label>
                                    <input type="datetime-local" class="form-control" id="start_date" ng-model="candidate.date" aria-describedby="emailHelp">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" id="location" ng-model="candidate.date" aria-describedby="emailHelp">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>