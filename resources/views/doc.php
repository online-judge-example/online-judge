<?php
/**
 * User
 * there are 3 type of user
 * 1. normal user
 *      login, update profile, visit problem, submit problem, visit submission history
 *
 * 2. problem setter
 *      problem setter also a normal user
 *      special activities:- create problem, update problem details, visit the submission history, visit user profile
 *      a setter can access only for his/her problem
 *
 * 3. admin
 *
 * libraries use for this project
 *  1. erusev/parsedown : (php library) a light weight markdown parser, require for parse problem description
 *  2. prismjs : (javascript library) use for syntax highlight
 */
