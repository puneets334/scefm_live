<?php
/**
 * Created by PhpStorm.
 * User: Mohit Jain
 * Date: 20/4/17
 * Time: 3:28 PM
 */
?>

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height: auto;">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
               <!-- <img src="" class="img-circle" alt="User Image">-->
            </div>

        </div>

        <ul class="sidebar-menu tree" data-widget="tree">

            <li class="header">MAIN NAVIGATION</li>

            <li >
                <a href="<?php echo base_url(); ?>index.php/Admin/admin_dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <?php
            $sessionarray=$this->session->userdata(); ?>
            <?php
            if($sessionarray['login']['employeeGroup'] == 'A'){ ?>
            <li class="treeview " id="master_task_types">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Master Task Types</span>
                    <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                     </span>
                </a>
                <ul class="treeview-menu">
                    <li id="add_task_types" >
                        <a href="<?php echo base_url(); ?>index.php/Admin/create_task_types">
                            <i class="fa fa-circle-o"></i> <span>Add Task Types </span>
                        </a>
                    </li>

                </ul>
            </li>


                <?php
                 //if($_SESSION['login']['desgname'] == "BRANCH OFFICER (SYSTEM ADMINISTRATOR)"){
                 if(1){
                ?>

            <li class="treeview " id="master_desgination">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Mappings</span>
                    <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                     </span>
                </a>
                <ul class="treeview-menu">
                    <!--<li id="add_desg_section" >
                        <a href="<?php /*echo base_url(); */?>index.php/Admin/designation_section_mappping">
                            <i class="fa fa-circle-o"></i> <span>Designation & Section Allocation </span>
                        </a>
                    </li>-->
                    <li id="add_desg_section" >
                        <a href="<?php echo base_url(); ?>index.php/Admin/registry_officials_mappping">
                            <i class="fa fa-circle-o"></i> <span>Registry Officials mapping </span>
                        </a>
                    </li>

                </ul>
            </li>

            <?php }}
            ?>
                <li class="treeview " id="master_project">
                    <a href="#">
                        <i class="fa fa-folder"></i> <span>Projects</span>
                        <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                     </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                            if($sessionarray['login']['employeeGroup'] == 'A'){ ?>
                            ?>
                            <li id="add_project" >
                                <a href="<?php echo base_url(); ?>index.php/Admin/create_project">
                                    <i class="fa fa-circle-o"></i><span>Add Project</span>
                                </a>
                            </li>

                            <li id="edit_project">
                                <a href="<?php echo base_url(); ?>index.php/Admin/edit_project">
                                    <i class="fa fa-circle-o"></i><span>Modify Project </span>
                                </a>
                            </li>
                        <?php }  ?>

                        <li id="project_timeline">
                            <a href="<?php echo base_url(); ?>index.php/Admin/project_timeline">
                                <i class="fa fa-circle-o"></i><span>Project TimeLine </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="treeview " id="master_task" >
                    <a href="#">
                        <i class="fa fa-folder"></i> <span>Tasks</span>
                        <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                     </span>
                    </a>
                    <ul class="treeview-menu">

                        <?php
                        if($sessionarray['login']['employeeGroup'] == 'A'){ ?>
                        ?>
                        <li id="add_task" >
                            <a href="<?php echo base_url(); ?>index.php/Admin/create_task">
                                <i class="fa fa-circle-o"></i><span>Task</span>
                            </a>
                        </li>
                        <li id="task_transfer">
                            <a href="<?php echo base_url(); ?>index.php/Admin/task_transfer">
                                <i class="fa fa-circle-o"></i><span>Task Transfer </span>
                            </a>
                        </li>

                        <?php } ?>

                        <li id="task_complete">
                            <a href="<?php echo base_url(); ?>index.php/Admin/task_history">
                                <i class="fa fa-circle-o"></i><span>Task Status</span>
                            </a>
                        </li>

                        <li id="project_assets">
                            <a href="<?php echo base_url(); ?>index.php/Admin/attach_report_details">
                                <i class="fa fa-circle-o"></i><span>Project Assets </span>
                            </a>
                        </li>

                        <li id="pending_task_user">
                            <a href="<?php echo base_url(); ?>index.php/Admin/task_pending_user_lists">
                                <i class="fa fa-circle-o"></i><span>Pending Task Information</span>
                            </a>
                        </li>

                        <li id="task_complete">
                            <a href="<?php echo base_url(); ?>index.php/Admin/task_completed">
                                <i class="fa fa-circle-o"></i><span>Completed Task Information </span>
                            </a>
                        </li>

                        <li id="task_timeline">
                            <a href="<?php echo base_url(); ?>index.php/Admin/task_timeline">
                                <i class="fa fa-circle-o"></i><span>Task Time Line </span>
                            </a>
                        </li>

                        <li id="task_timeline">
                            <a href="<?php echo base_url(); ?>index.php/Admin/subordinates_tasks">
                                <i class="fa fa-circle-o"></i><span>Subordinates Task </span>
                            </a>
                        </li>
                    </ul>
                </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>