<div class="content mt-3">
                        <?php 
                        $i= 1;
                        while ($i <= 6) {

                            ?>
                                <div class="col-sm-4 col-sm-3 col-lg-3">
                                    <div class="card-header user-header alt bg-dark">
                                        <div class="text-sm-center text-white">
                                            <h1><?php echo $building.$i; ?></h1>
                                        </div>
                                        <div class="text-sm-center text-white card-title">
                                            <strong class="mb-3">
                                                <?php echo $deptaing; ?>
                                            </strong>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <span class="text-sm-center">
                                                <h1>0</h1>
                                                <h5>Target Per Hours</h5>
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text-sm-center">
                                                <h1>0</h1>
                                                <h5>Daily Target</h5>
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text-sm-center">
                                                <h1>0</h1>
                                                <h5>Actual Output</h5>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="outer_oph">
                                    <?php
                                        $j = 1;
                                        $start = strtotime('08:00');
                                        while ($j <= 8) {
                                        ?>
                                        <div class="col-sm-6 col-lg-3 col-md-3 ">
                                                        <div class="card text-white bg-flat-color-4 ">
                                                            <div class="card-header ">
                                                                <h6 class="text-sm-center text-white" class=" "><?php echo date('H:i', $start)." - ".date('H:i', strtotime('+1 hours', $start)); ?></h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <h2 class="text-white text-sm-center count">
                                                                 0
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                        <?php 
                                         $start = strtotime('+1 hours', $start);
                                                            if ($start == strtotime('12:00')) {
                                                                $start = strtotime('13:00');
                                                            }
                                                            $j++;
                                                        }
                                    ?>
                                </div>
                            <?php

                            $i++;
                        }

                         ?>
                    </div>