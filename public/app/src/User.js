import React, { Component } from 'react';

class User extends Component {

    onClick = (e) => {
        e.preventDefault();

        this.props.selectUser(
            this.props.id
        );
    }

    render() {
        const { userName, avatar } = this.props;

        return(
            <div>
                <li>
                    <span>
                        <img className="avatar-image" src={`${avatar}`} height="23" />
                        <a href="#" onClick={ this.onClick }>{ userName }</a>
                    </span>
                </li>
            </div>
        );
    }
}

export default User;
