// Element holder version conflict detection
// Polls every 3 seconds; shows a persistent banner and disables the save button when a newer version exists.
// The backend also guards against overwrites via VersionConflictException.
(function () {
    $(document).ready(function () {
        var elementHolderId = $('#element_holder_id').val();
        var elementHolderVersion = parseInt($('#element_holder_version').val()) || null;

        if (!elementHolderId || elementHolderVersion === null) {
            return;
        }

        var latestKnownVersion = elementHolderVersion;
        var versionConflict = false;
        var bannerMessage = '{$text_resources.element_holder_version_conflict_banner|escape:'javascript'}';

        console.log('[version-check] elementHolderId:', elementHolderId, 'elementHolderVersion:', elementHolderVersion);

        $('body').append('<div id="version-conflict-banner">' + bannerMessage + '</div>');

        function onVersionConflict() {
            if (!versionConflict) {
                versionConflict = true;
                $('#version-conflict-banner').show();
                $('#update_element_holder').addClass('action-button-disabled');
            }
        }

        $('#element_holder_form_id').on('submit.versioncheck', function (e) {
            if (versionConflict) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }
        });

        setInterval(function () {
            $.ajax({
                url: '/admin/api/element-holder/version?id=' + elementHolderId,
                method: 'GET',
                success: function (response) {
                    console.log('[version-check] poll response:', response, 'latestKnownVersion:', latestKnownVersion);
                    if (response && response.version > latestKnownVersion) {
                        console.log('[version-check] conflict! DB version', response.version, '> local', latestKnownVersion);
                        latestKnownVersion = response.version;
                        onVersionConflict();
                    }
                },
                error: function (xhr, status, error) {
                    console.warn('[version-check] poll failed:', status, error);
                }
            });
        }, 3000);
    });
})();
