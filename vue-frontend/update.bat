@echo off
echo ========================================
echo Malik Electronics - Apply Design Update
echo ========================================
echo.
REM --- Set this to the folder where you downloaded the files ---
set DOWNLOADS=%USERPROFILE%\Downloads
REM Layout components
copy /Y "%DOWNLOADS%\Footer.vue" "src\components\layout\Footer.vue"
copy /Y "%DOWNLOADS%\Header.vue" "src\components\layout\Header.vue"
copy /Y "%DOWNLOADS%\TopBar.vue" "src\components\layout\TopBar.vue"
copy /Y "%DOWNLOADS%\NavigationBar.vue" "src\components\layout\NavigationBar.vue"
REM Home components
copy /Y "%DOWNLOADS%\HeroBanner.vue" "src\components\home\HeroBanner.vue"
copy /Y "%DOWNLOADS%\TodaysDeals.vue" "src\components\home\TodaysDeals.vue"
copy /Y "%DOWNLOADS%\ProductCarousel.vue" "src\components\home\ProductCarousel.vue"
copy /Y "%DOWNLOADS%\CategoryGrid.vue" "src\components\home\CategoryGrid.vue"
copy /Y "%DOWNLOADS%\TopCategories.vue" "src\components\home\TopCategories.vue"
copy /Y "%DOWNLOADS%\TopBrands.vue" "src\components\home\TopBrands.vue"
REM View
copy /Y "%DOWNLOADS%\HomeView.vue" "src\views\HomeView.vue"
echo.
echo ========================================
echo Done! Now run: npm install mitt
echo Then run: npm run dev
echo ========================================
pause