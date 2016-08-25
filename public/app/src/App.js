import React, { Component } from 'react';
import Users from './Users';
import Content from './Content';
import $ from 'jquery';

class App extends Component {

    apiUrl = 'http://192.168.99.100:8080';

    constructor(props) {
        super(props);

        this.getUsers = this.getUsers.bind(this);

        this.state = {
            users: []
        };

        this.getUsers();
    }

    getUsers() {
        $.ajax({
            url: this.apiUrl + '/api/users',
            type: 'GET'
        }).done((data) => {
            var state = this.state;
            state.users = data;
            this.setState(state);
        })
    }

    selectUser(userId) {
        $.ajax({
            url: this.apiUrl +'/api/users/' + userId,
            type: 'GET'
        }).done((selectedUser) => {
            var state = this.state;
            state.selectedUser = selectedUser;
            this.setState(state);
        });
    }

    render() {

        return (
            <div className="container">
                <div className="row">
                    <h1>Six degrees of Bacon</h1>
                    <Users selectUser={ this.selectUser.bind(this) } users={ this.state.users }/>
                    <Content />
                </div>
            </div>
        );
    }
}

export default App;
