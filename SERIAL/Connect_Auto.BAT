@echo off
echo ***********************
echo * (c) RoboCallz, 2017 *
echo ***********************
setlocal

for /f "delims=" %%I in (
'wmic path Win32_SerialPort Where "Description LIKE 'Silicon Labs CP210%%'" get DeviceID^,Description^ /format:list ^| find "="'
) do (
    set "%%I"
)

IF "%DeviceID%" == "" ( 
	ECHO No RECORDER found
	PAUSE
	exit 
)
PuTTY.exe -serial %DeviceID% -sercfg "115200,8,n,1,N"
pause
exit