# Facebook-Album-Downloader
Facebook Album Downloader is PHP based web application it’s helps us to download our Facebook album and also upload it to the drive.
## Feature for User
* You can **login with Facebook**. If you are already login in Facebook then application only ask you to continue with that account.
* You can view your **facebook album slideshow**- It will start showing photos in that album also in full-screen mode
* You can **download your album** in zip file and also provide option to download selected albums or all the albums.
* You can also **upload your album** into the google drive with selected or all option.
* You can also **logout** from the Facebook-Album-Downloader. (It will logout from your Facebook)
## System Functionality
* When user wants to download album - subfolder is created for album photos inside ‘Download’ directory and that will be deleted immediately after creating zip file for user. That will maintain the privacy of user.
* When user wants to upload album – subfolder is created inside ‘Upload’ directory and all the subfolder will be upload into the `facebook_<username>_album` where <username> will be the Facebook username of user, it will also be deleted after uploading albums.
* Facebook-Album-Downloader will asked user to connect with their Google account only once, no matter how many times they choose to move data.
## Link
The Facebook-Album-Downloader is hosted on https://patelmargi.azurewebsites.net/FacebookAlbumDownloader
## Instruction
Facebook `Album-Downloader-App` is not review yet. so, If you want to connect with Facebook-Album-Downloader you notify me and perform following steps:
1. First, you need to confirm the invitation of Album-Downloader-App app from your Facebook.
2. Open the https://developers.facebook.com/tools/explorer/ and select the Album-Downloader-App Application from the top-right drop-down menu and click get token below from the application menu and select Get User Access Token.
3. That will open the Select Permission popup and choose the user_photos checkbox and click on the Get Access Token. It will provide the accessibility for your Facebook album.
4. Now, Refresh the page of Facebook Album Downloader or try again.
