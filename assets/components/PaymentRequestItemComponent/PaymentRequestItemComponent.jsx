import React, { useState, useRef, useEffect } from 'react';
// import PropTypes from 'prop-types';
import Api from "../../scripts/Api.js";

const PaymentRequestItemComponent = (props) => {
    const [state, setState] = useState(null);

    useEffect(()=>{
        let api = new Api();
        let formData = new FormData();
        formData.append('vendor_token', VENDOR_TOKEN);
        formData.append('request_uuid', props.payment.requestUuid);
        api.post(API_REQUEST_STATE_URL, formData).then((response)=>{
            setState(response.state);
        }).catch((error)=>{
            console.log(error);
        });

    });

    if(state === null){
        return null;
    }

    return (
        <React.Fragment>
            <tr>
                <td>{props.payment.firstname}</td>
                <td>{props.payment.lastname}</td>
                <td>{props.payment.email}</td>
                <td>{props.payment.amount}</td>
                <td>{props.payment.createdAt}</td>
                <td>{state}</td>
            </tr>
        </React.Fragment>
    );
};

export default PaymentRequestItemComponent;