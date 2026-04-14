// Element holder version conflict detection
// Polls every 3 seconds; shows a persistent banner and disables the save button when a newer version exists.
// The backend also guards against overwrites via VersionConflictException.
// REST calls that modify the element holder dispatch 'element-holder-version-synced' so latestKnownVersion stays current.
(function () {
    $(document).ready(function () {
        var elementHolderId = $('#element_holder_id').val();
        var elementHolderVersion = parseInt($('#element_holder_version').val()) || null;

        if (!elementHolderId || elementHolderVersion === null) {
            return;
        }

        var latestKnownVersion = elementHolderVersion;
        var lastSeenDbVersion = elementHolderVersion;
        var versionConflict = false;
        var bannerMessage = '{$text_resources.element_holder_version_conflict_banner|escape:'javascript'}';

        $('body').append('<div id="version-conflict-banner">' + bannerMessage + '</div>');

        function onVersionConflict() {
            if (!versionConflict) {
                versionConflict = true;
                $('#version-conflict-banner').show();
                $('#update_element_holder').addClass('action-button-disabled');
            }
        }

        function resolveVersionConflict() {
            if (versionConflict) {
                versionConflict = false;
                $('#version-conflict-banner').hide();
                $('#update_element_holder').removeClass('action-button-disabled');
            }
        }

        // REST calls that update the element holder (image select, photo album, etc.)
        // dispatch this event with the new version so we stay in sync
        $(document).on('element-holder-version-synced', function (e, newVersion) {
            if (newVersion > latestKnownVersion) {
                latestKnownVersion = newVersion;
                $('#element_holder_version').val(newVersion);
            }
            // If our own REST call caught up to the last DB version the poll saw, clear the conflict
            if (versionConflict && latestKnownVersion >= lastSeenDbVersion) {
                resolveVersionConflict();
            }
        });

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
                    if (response && response.version > latestKnownVersion) {
                        lastSeenDbVersion = response.version;
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
