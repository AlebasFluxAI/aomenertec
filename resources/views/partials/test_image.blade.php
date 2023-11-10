<!DOCTYPE html>
<html>
<head>
    <!-- **DO THIS**: -->
    <!--   Replace SDK_VERSION_NUMBER with the current SDK version number -->
    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1478.0.min.js"></script>
</head>
<body>
<h1>Photo Album Viewer</h1>
<div id="viewere"/>
<script>
    // **DO THIS**:
    //   Replace BUCKET_NAME with the bucket name.
    //
    var albumBucketName = 'esfabucket';

    // **DO THIS**:
    //   Replace this block of code with the sample code located at:
    //   Cognito -- Manage Identity Pools -- [identity_pool_name] -- Sample Code -- JavaScript
    //
    // Initialize the Amazon Cognito credentials provider
    AWS.config.region = 'us-east-2'; // Region
    AWS.config.credentials = new AWS.CognitoIdentityCredentials({
        IdentityPoolId: 'us-east-2:2b76acf1-cb8a-4bee-bce3-53318747b494',
    });

    // Create a new service object
    var s3 = new AWS.S3({
        apiVersion: '2006-03-01',
        params: {Bucket: albumBucketName}
    });

    // A utility function to create HTML.
    function getHtml(template) {
        return template.join('\n');
    }

    // List the photo albums that exist in the bucket.
    function listAlbums() {
        s3.listObjects({Delimiter: '/'}, function (err, data) {
            if (err) {
                return alert('There was an error listing your albums: ' + err.message);
            } else {
                var albums = data.CommonPrefixes.map(function (commonPrefix) {
                    var prefix = commonPrefix.Prefix;
                    var albumName = decodeURIComponent(prefix.replace('/', ''));
                    return getHtml([
                        '<li>',
                        '<button style="margin:5px;" onclick="viewAlbum(\'' + albumName + '\')">',
                        albumName,
                        '</button>',
                        '</li>'
                    ]);
                });
                var message = albums.length ?
                    getHtml([
                        '<p>Click on an album name to view it.</p>',
                    ]) :
                    '<p>You do not have any albums. Please Create album.';
                var htmlTemplate = [
                    '<h2>Albums</h2>',
                    message,
                    '<ul>',
                    getHtml(albums),
                    '</ul>',
                ]
                document.getElementById('viewere').innerHTML = getHtml(htmlTemplate);
            }
        });
    }

    // Show the photos that exist in an album.
    function viewAlbum(albumName) {
        var albumPhotosKey = encodeURIComponent(albumName) + '/';
        s3.listObjects({Prefix: albumPhotosKey}, function (err, data) {
            if (err) {
                return alert('There was an error viewing your album: ' + err.message);
            }
            // 'this' references the AWS.Request instance that represents the response
            var href = this.request.httpRequest.endpoint.href;
            var bucketUrl = href + albumBucketName + '/';

            var photos = data.Contents.map(function (photo) {
                var photoKey = photo.Key;
                var photoUrl = bucketUrl + encodeURIComponent(photoKey);
                return getHtml([
                    '<span>',
                    '<div>',
                    '<br/>',
                    '<img style="width:128px;height:128px;" src="' + photoUrl + '"/>',
                    '</div>',
                    '<div>',
                    '<span>',
                    photoUrl,
                    '</span>',
                    '</div>',
                    '</span>',
                ]);
            });
            var message = photos.length ?
                '<p>The following photos are present.</p>' :
                '<p>There are no photos in this album.</p>';
            var htmlTemplate = [
                '<div>',
                '<button onclick="listAlbums()">',
                'Back To Albums',
                '</button>',
                '</div>',
                '<h2>',
                'Album: ' + albumName,
                '</h2>',
                message,
                '<div>',
                getHtml(photos),
                '</div>',
                '<h2>',
                'End of Album: ' + albumName,
                '</h2>',
                '<div>',
                '<button onclick="listAlbums()">',
                'Back To Albums',
                '</button>',
                '</div>',
            ]
            document.getElementById('viewere').innerHTML = getHtml(htmlTemplate);
            document.getElementsByTagName('img')[0].setAttribute('style', 'display:none;');
        });
    }

    function listSubfolders() {
        // List objects in the "Rorro" folder
        s3.listObjects({Prefix: 'Rorro/'}, function (err, data) {
            if (err) {
                return alert('There was an error listing subfolders: ' + err.message);
            } else {
                // Get the contents (subfolders) of "Rorro"
                var subfolders = data.CommonPrefixes.map(function (commonPrefix) {
                    var prefix = commonPrefix.Prefix;
                    var folderName = decodeURIComponent(prefix.replace('Rorro/', ''));
                    return getHtml([
                        '<li>',
                        '<button style="margin:5px;" onclick="viewSubfolder(\'' + folderName + '\')">',
                        folderName,
                        '</button>',
                        '</li>'
                    ]);
                });

                var message = subfolders.length ?
                    getHtml([
                        '<p>Click on a subfolder to view its contents.</p>',
                    ]) :
                    '<p>No subfolders found in "Rorro".';

                var htmlTemplate = [
                    '<h2>Subfolders in "Rorro"</h2>',
                    message,
                    '<ul>',
                    getHtml(subfolders),
                    '</ul>',
                ]
                document.getElementById('viewere').innerHTML = getHtml(htmlTemplate);
            }
        });
    }

    function viewSubfolder(subfolderName) {
        var subfolderKey = 'Rorro/' + subfolderName + '/';
        s3.listObjects({Prefix: subfolderKey}, function (err, data) {
            if (err) {
                return alert('There was an error viewing the subfolder: ' + err.message);
            }

            var photos = data.Contents.map(function (photo) {
                var photoKey = photo.Key;
                var photoUrl = s3.getSignedUrl('getObject', {Bucket: albumBucketName, Key: photoKey});
                return getHtml([
                    '<span>',
                    '<div>',
                    '<br/>',
                    '<img style="width:128px;height:128px;" src="' + photoUrl + '"/>',
                    '</div>',
                    '<div>',
                    '<span>',
                    photoKey.replace(subfolderKey, ''),
                    '</span>',
                    '</div>',
                    '</span>',
                ]);
            });

            var message = photos.length ?
                '<p>The following photos are present in subfolder "' + subfolderName + '":</p>' :
                '<p>There are no photos in this subfolder.</p>';

            var htmlTemplate = [
                '<div>',
                '<button onclick="listSubfolders()">',
                'Back To Subfolders',
                '</button>',
                '</div>',
                '<h2>',
                'Subfolder: ' + subfolderName,
                '</h2>',
                message,
                '<div>',
                getHtml(photos),
                '</div>',
                '<h2>',
                'End of Subfolder: ' + subfolderName,
                '</h2>',
                '<div>',
                '<button onclick="listSubfolders()">',
                'Back To Subfolders',
                '</button>',
                '</div>',
            ]
            document.getElementById('viewere').innerHTML = getHtml(htmlTemplate);
            document.getElementsByTagName('img')[0].setAttribute('style', 'display:none;');
        });
    }


    listAlbums()
</script>
</body>
</html>
