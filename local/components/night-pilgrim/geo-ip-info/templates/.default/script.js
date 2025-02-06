class GeoIpJSObject {

    constructor(objParams) {
        this.signedParameters = objParams.signedParameters;
        this.componentName = objParams.componentName;
    }

    getIpInfo(formData) {
        let thisObj = this;
        let data = this.prepareFormData(formData);
        BX.ajax.runComponentAction(this.componentName, 'getIpInfo', {
            mode: 'class',
            signedParameters: this.signedParameters,
            data: {inputFields: data}
        }).then(function (response) {
            let result = JSON.parse(response.data);
            thisObj.insert2Html(result)
        }).catch((response) => {
            thisObj.insert2Html(response);
        });
    }

    prepareFormData(formData) {
        let data = [];
        for (let pair of formData.entries()) {
            data.push({'name': pair[0], 'value': pair[1]});
        }
        return data;
    }

    insert2Html(result) {
        let insertBlock = document.querySelector('[data-result-info]');
        if (insertBlock) {
            let html = '<ul class="list-group">';
            if (result.status && result.status === 'error') {
                if (result.errors.length > 0) {
                    for (let i = 0; i < result.errors.length; i++) {
                        html += '<li class="list-group-item alert alert-danger">Error: '
                            + result.errors[i].message + '</li>';
                    }
                }
            } else {
                html += '<li class="list-group-item">' +
                    'Континент: <input type="text" class="form-control" value="'
                    + result.UF_CONTINENT_NAME + '" disabled >' +
                    '</li>' +
                    '<li class="list-group-item">' +
                    'Страна: <input type="text" class="form-control" value="'
                    + result.UF_COUNTRY_NAME + '" disabled >' +
                    '</li>' +
                    '<li class="list-group-item">' +
                    'Регион: <input type="text" class="form-control" value="'
                    + result.UF_REGION_NAME + '" disabled >' +
                    '</li>' +
                    '<li class="list-group-item">' +
                    'Город: <input type="text" class="form-control" value="'
                    + result.UF_CITY + '" disabled >' +
                    '</li>' +
                    '<li class="list-group-item">' +
                    'Координаты: <input type="text" class="form-control" value="'
                    + result.UF_LATITUDE + ', ' + result.UF_LONGITUDE + '" disabled >' +
                    '</li>';
            }
            html += '</ul>';
            insertBlock.innerHTML = html;
        }
    }

}

BX.ready(() => {
    let form = document.querySelector('[data-get-ip-info]');
    if (form) {
        BX.bind(form, 'submit', function(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            geoIpObj.getIpInfo(formData);
        })
    }
})