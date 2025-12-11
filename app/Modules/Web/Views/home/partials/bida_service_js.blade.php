<script>
    $(function() {

        let isLoadBbsCode = false;
        document.getElementById('loadBusinessSector').onclick = function () {
            loadBBSCode();
        };
        function loadBBSCode() {
            if (!isLoadBbsCode) {
                $.ajax({
                    url: "/web/get-bbs-code",
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    success: function (response) {
                        $("#businessSector").html(response.response);
                        isLoadBbsCode = true;
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    },
                    complete: function () {
                        $('#businessSectorLoading').hide();
                    }
                });
            }
        }

        let isAvailableServiceLoaded = false;
        document.getElementById('loadAvailableServices').onclick = function () {
            LoadAvailableService();
        };
        function LoadAvailableService() {
            if (!isAvailableServiceLoaded) {
                $.ajax({
                    url: "/web/get-available-services",
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        $("#availableServices").html(response.response);
                        isAvailableServiceLoaded = true;
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    },
                    complete: function () {
                        $('#availableServiceLoading').hide();
                    }
                });
            }
        }

        let isNecessaryResourcesLoaded = false;
        document.getElementById('loadNecessaryResources').onclick = function () {
            LoadNecessaryResources();
        };
        function LoadNecessaryResources() {
            if (!isNecessaryResourcesLoaded) {
                $.ajax({
                    url: "/web/necessary-resources",
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        $("#necessaryResources").html(response.response);
                        isNecessaryResourcesLoaded = true;
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    },
                    complete: function () {
                        $('#necessaryResourcesLoading').hide();
                    }
                });
            }
        }

        let isAgencyLoaded = false;
        document.getElementById('loadIpaClpAgency').onclick = function () {
            loadIpaClpAgencyList();
        };
        function loadIpaClpAgencyList() {
            if (!isAgencyLoaded) {
                $.ajax({
                    url: "/web/get-agency-list",
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        $("#ipaClpAgency").html(response.response);
                        isAgencyLoaded = true;
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    },
                    complete: function () {
                        $("#ipaClpAgencyLoading").hide();
                    }
                });
            }
        }
    });
</script>
