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
  esptool.exe --chip esp32 --port %DeviceID% --baud 921600 --before default_reset --after hard_reset write_flash -z --flash_mode dio --flash_freq 80m --flash_size detect 0xe000 boot_app0.bin 0x1000 bootloader_qio_80m.bin 0x10000 s_JEC_29_NW.ino.bin 0x8000 s_JEC_29_NW.ino.partitions.bin
  pause
)
exit

