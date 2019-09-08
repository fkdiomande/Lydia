import Api from "../Api.js";
import Helper from "../Helper.js";

let api = new Api();

Helper.addEvent(document.getElementById('payment-request-form'), 'submit', (e)=>{
    e.preventDefault();
    // Check form and save it
    api.post(e.target.action, new FormData(e.target))
        .then((response)=>{
            let formData = new FormData();
            Object.keys(response.data.apiParams).map((param)=>{
                formData.append(param, response.data.apiParams[param]);
            });
            // Send payment request
            api.post(response.data.apiRequestDoUrl, formData)
                .then((lydiaApiResponse)=>{
                    console.log(lydiaApiResponse);
                    // Save request uuid, request id and request error
                    api.post(response.data.savePaymentRequestUuid, {
                        error: lydiaApiResponse.error,
                        requestId: lydiaApiResponse.request_id,
                        requestUuid: lydiaApiResponse.request_uuid,
                        uniqueId: response.data.uniqueId
                    })
                    .then((result)=>{
                        console.log(result);
                    })
                    .catch((error)=>{
                        console.log(error);
                    });
                })
                .catch((error)=>{
                    console.error(error);
                })
        })
        .catch((error)=>{
            console.error(error);
        });
});