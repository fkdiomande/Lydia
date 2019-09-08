import React from 'react';
import ReactDOM from 'react-dom';
import PaymentRequestItemComponent from "../../components/PaymentRequestItemComponent/PaymentRequestItemComponent.jsx";

const PaymentRequestAdminContainer = () =>{

    return (
        <React.Fragment>

            <table style={{width: '100%'}}>
                <thead>
                    <tr style={{'textAlign': 'left'}}>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Created at</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                {
                    PAYMENT_REQUESTS.map((payment, key)=>{
                        return <PaymentRequestItemComponent payment={payment} key={key} />
                    })
                }
                </tbody>
            </table>
        </React.Fragment>
    );

};

ReactDOM.render(
    <PaymentRequestAdminContainer />,
    document.getElementById('container__payment-request-admin-dom-container')
);
