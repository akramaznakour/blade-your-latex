@echo OFF

set INPUT_FULL_FILE_NAME="./../output/tex/%~1.tex"
set OUTPUT_DIRECTORY="./../output/pdf"
set OUTPUT_FILE_PATH="./../output/pdf/%~1.pdf"

xcopy /s/e "assets" "processing"
xcopy /s/e "./output/tex" "processing"

cd "processing"

set aa = %1%
lualatex  %INPUT_FULL_FILE_NAME%
xcopy /s/e "*.pdf" %OUTPUT_DIRECTORY% 
start "" %OUTPUT_FILE_PATH%

cd ../
del /Q processing

@REM run.bat curriculum-vitae