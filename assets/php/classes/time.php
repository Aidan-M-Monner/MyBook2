<?php 
    Class Time {
        function get_time($pastTime, $today = 0, $differenceFormat = '%y') {
            $today = date("Y-m-d H:i:s");
            $datetime1 = date_create($pastTime);
            $datetime2 = date_create($today);

            // Format for Year //
            $interval = date_diff($datetime1, $datetime2);
            $answerY = $interval->format($differenceFormat);

            // Format for Month //
            $differenceFormat = '%m';
            $answerM = $interval->format($differenceFormat);

            // Format for Day //
            $differenceFormat = '%d';
            $answerD = $interval->format($differenceFormat);

            // Format for Hour //
            $differenceFormat = '%h';
            $answerH = $interval->format($differenceFormat);

            // Check for how much time has passed //
            if($answerY >= 1) {
                // One Year Passed //
                $answerY = date(" F jS, Y ", strtotime($pastTime));
                return $answerY;
            } else if($answerM >= 1) {
                // One Month Passed //
                $answerM = date(" F jS, Y ", strtotime($pastTime));
                return $answerM;
            } else if($answerD > 2) {
                // 2+ Days Passed //
                $answerD = date(" F jS, Y ", strtotime($pastTime));
                return $answerD;
            } else if($answerD == 2) {
                // 2 Days Passed //
                return $answerD . " d, " . $answerH . " hr ago";
            } else if($answerD == 1) {
                // 1 Day Passed //
                return "1 d, " . date("h:i:s a", strtotime($pastTime));
            } else {
                // Less Than A Day //
                $differenceFormat = '%h';
                $answerH = $interval->format($differenceFormat);

                $differenceFormat = '%i';
                $answerI = $interval->format($differenceFormat);

                if(($answerH < 24) && ($answerH > 1)) {
                    return $answerD . " hr, " . $answerI . " min ago";
                } else if($answerH == 1) {
                    return "an hour ago";
                } else {
                    $differenceFormat = '%i';
                    $answerI = $interval->format($differenceFormat);

                    if(($answerI < 60) && ($answerI > 1)) {
                        return $answerI . " minutes ago";
                    } else if($answerI == 1) {
                        return "a minute ago";
                    } else {
                        $differenceFormat = '%s';
                        $answerS = $interval->format($differenceFormat);

                        if(($answerS < 60) && ($answerS > 10)) {
                            return $answerS . " seconds ago";
                        } else if($answerS < 10) {
                            return "few seconds ago";
                        }
                    }
                }
            }
        }
    }