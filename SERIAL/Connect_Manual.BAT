@echo off
echo ***********************
echo * (c) RoboCallz, 2017 *
echo ***********************
PuTTY.exe -serial COM4 -sercfg "9600,8,n,1,N"
rem                  ^- put port number of the Device here
pause
