import React, { useState, useEffect } from "react";

const Forum = () => {
    const [questions, setQuestions] = useState([]);

    useEffect(() => {
        fetch("/api/questions")
            .then(res => res.json())
            .then(data => setQuestions(data));
    }, []);

    return (
        <div>
            <h1>Hacker Community Forum</h1>
            {questions.map((question) => (
                <div key={question.id}>
                    <h2>{question.title}</h2>
                    <p>{question.content}</p>
                </div>
            ))}
        </div>
    );
};

export default Forum;
