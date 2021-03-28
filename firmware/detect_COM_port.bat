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
	ECHO No Device found
	PAUSE
	exit 
) ELSE (
  ECHO Device is on %DeviceID% 
)
exit
