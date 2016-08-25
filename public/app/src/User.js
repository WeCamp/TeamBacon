import React, { Component } from 'react';

class User extends Component {

    constructor(props) {
        super(props);

        this.onClick = this.onClick.bind(this);
    }

    onClick(e) {
        e.preventDefault();

        this.props.selectUser(
            this.props.id
        );
    }

    render() {
        var { userName, avatar } = this.props;

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
