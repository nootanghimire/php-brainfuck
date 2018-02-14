<?php

    function compile(string $brainfuck, $debug = false): array
    {
        $program = [];
        $pointer = 0;
        $ip = 0;
        $prog_length = strlen($brainfuck);
        $loopStack = [];
        while (true) {
            $instr = $brainfuck[$ip];
            $ip_move = true;
            switch ($instr) {
            case '>':
                $pointer++;
                $debug && print ("\n[INF] Increment Data Pointer to: " . $pointer);
                break;

            case '<':
                $pointer--;
                $debug && print ("\n[INF] Decrement Data Pointer to: " . $pointer);
                break;

            case '+':
                $program[$pointer] = isset($program[$pointer]) ? $program[$pointer] + 1 : 1;
                $debug && print ("\n[INF] Increment value in Data Pointer: " . $pointer . ". Value: " . $program[$pointer]);
                break;

            case '-':
                $program[$pointer] = isset($program[$pointer]) ? $program[$pointer] - 1 : -1;
                $debug && print ("\n[INF] Decrement value in Data Pointer: " . $pointer . ". Value: " . $program[$pointer]);
                break;

            case '.':
                fputs(STDOUT, $program[$pointer]);
                $debug && print ("\n[INF] Write current value to STDOUT. Current Value: " . $program[$pointer]);
                break;

            case ',':
                $program[$pointer] = fgetc(STDIN);
                $debug && print ("\n[INF] Got value from user: " . $program[$pointer]);
                break;

            case '[':
                if (!isset($program[$pointer])) {
                    $program[$pointer] = 0;
                }

                $ip_move = $program[$pointer] != 0;
                array_push($loopStack, $ip);
                if (!$ip_move) {
                    $pos = strpos($brainfuck, ']', $ip);
                    $ip = $pos + 1;
                    $debug && print ("\n[INF][IP] Move Instruction pointer to " . $ip);
                }

                break;

            case ']':
                if (!isset($program[$pointer])) {
                    $program[$pointer] = 0;
                }

                $ip_move = $program[$pointer] == 0;

                // pop last position of `]`

                $pos = array_pop($loopStack);
                if (!$ip_move) {

                    // push it back

                    array_push($loopStack, $pos);
                    $ip = $pos + 1;
                    $debug && print ("\n[INF][IP] Move Instruction pointer to " . $ip);
                }

                break;
            }

            if ($ip_move) {
                $ip++;
                $debug && print ("\n[INF][IP] Move Instruction pointer to " . $ip);
            }

            if ($ip >= $prog_length) break;
        }

        return $program;
    }
