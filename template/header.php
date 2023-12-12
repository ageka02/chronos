<header id="header" class="header ">
            <div class="header-menu">
                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">                         
                        <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#tampilaku">
                            <i class="fa fa-search"></i> Filter
                        </button>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="../images/user1.png" alt="User Avatar">
                        </a>
                        <div class="user-menu dropdown-menu">
                            <li class="" ><i class="fa fa-user"></i> <?php echo $_SESSION['name']; ?></li>
                            <li><a class="nav-link" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </div>
                    </div>
                </div>
            </div>
        </header>